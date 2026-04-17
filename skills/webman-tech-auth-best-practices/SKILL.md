---
name: webman-tech-auth-best-practices
description: webman-tech/auth 最佳实践。使用场景：用户配置认证授权时，给出明确的推荐写法。
---

# webman-tech/auth 最佳实践

## 核心原则

1. **Guard 是认证上下文**：一个 guard 对应一套用户体系（web/api/admin 等）
2. **三件套**：每个 guard 必须配置 `identityRepository`（查用户）、`authenticationMethod`（取凭证）、`authenticationFailureHandler`（失败处理）
3. **Web 用 Redirect，API 用 ThrowException**

---

## 实现用户模型

两个接口职责不同，可以分开实现，也可以合并到同一个类：

- `IdentityInterface` — 代表已认证的用户，实现 `getId()` 和 `refreshIdentity()`
- `IdentityRepositoryInterface` — 负责根据 token/id 查找用户，实现 `findIdentity()`

### 合并实现（简单场景，常见写法）

```php
class User extends Model implements IdentityInterface, IdentityRepositoryInterface
{
    public function getId(): ?string
    {
        return (string) $this->id;
    }

    public function refreshIdentity(): static
    {
        return $this->fresh() ?? $this;
    }

    public function findIdentity(string $token, ?string $type = null): ?IdentityInterface
    {
        return static::where('access_token', $token)->first();
    }
}
```

### 分开实现（复杂场景）

当查找逻辑较复杂，或需要注入依赖时，可以分开：

```php
// 用户模型只实现 IdentityInterface
class User extends Model implements IdentityInterface
{
    public function getId(): ?string { return (string) $this->id; }
    public function refreshIdentity(): static { return $this->fresh() ?? $this; }
}

// 单独的 Repository 实现 IdentityRepositoryInterface
class UserRepository implements IdentityRepositoryInterface
{
    public function findIdentity(string $token, ?string $type = null): ?IdentityInterface
    {
        // 可以注入缓存、多表查询等复杂逻辑
        return User::where('access_token', $token)->where('status', 1)->first();
    }
}

// Guard 配置中使用 Repository
'identityRepository' => fn() => new UserRepository(),
```

---

## 配置 Guard

```php
// config/plugin/webman-tech/auth/auth.php
use WebmanTech\Auth\Authentication\FailureHandler\RedirectHandler;
use WebmanTech\Auth\Authentication\FailureHandler\ThrowExceptionHandler;
use WebmanTech\Auth\Authentication\Method\SessionMethod;
use WebmanTech\Auth\Authentication\Method\HttpBearerMethod;
use WebmanTech\Auth\Interfaces\IdentityRepositoryInterface;

return [
    'default' => 'web',
    'guards' => [

        // Web 用户：Session 认证 + 失败跳转登录页
        'web' => [
            'identityRepository' => fn() => new app\model\User(),
            'authenticationMethod' => fn(IdentityRepositoryInterface $repo) => new SessionMethod($repo),
            'authenticationFailureHandler' => fn() => new RedirectHandler(route('login')),
        ],

        // API 用户：Bearer Token 认证 + 失败抛异常
        'api' => [
            'identityRepository' => fn() => new app\model\User(),
            'authenticationMethod' => fn(IdentityRepositoryInterface $repo) => new HttpBearerMethod($repo),
            'authenticationFailureHandler' => fn() => new ThrowExceptionHandler(),
        ],
    ],
];
```

---

## 认证方式选择

| 场景 | 推荐方式 |
|------|----------|
| 传统 Web 后台 | `SessionMethod` |
| REST API | `HttpBearerMethod`（`Authorization: Bearer <token>`） |
| 自定义 Header | `HttpHeaderMethod`（默认 `X-Api-Key`） |
| Query/Post 参数 | `RequestMethod`（默认 `access-token`） |
| JWT（tinywan/jwt） | `TinywanJwtMethod` |
| 多种方式并存 | `CompositeMethod` |

### CompositeMethod：同时支持多种认证方式

```php
use WebmanTech\Auth\Authentication\Method\CompositeMethod;

'authenticationMethod' => fn(IdentityRepositoryInterface $repo) => new CompositeMethod([
    new SessionMethod($repo),      // 先尝试 session
    new HttpBearerMethod($repo),   // 再尝试 Bearer token
]),
```

---

## 失败处理选择

| 场景 | 推荐处理器 |
|------|-----------|
| Web 页面 | `RedirectHandler('/login')` — 跳转登录页 |
| REST API | `ThrowExceptionHandler` — 抛出 `UnauthorizedException`，交给框架统一处理 |
| 简单 API | `ResponseHandler` — 直接返回 401 |

---

## 中间件配置

### 单 guard 场景

```php
use WebmanTech\Auth\Middleware\Authentication;

return [
    '' => [Authentication::class],
];
```

### 多 guard 场景：用 SetAuthGuard 切换

**webman 中间件不支持带参数构造**，需要为每个 guard 创建无参数子类：

```php
// app/middleware/SetAuthGuardAdmin.php
class SetAuthGuardAdmin extends SetAuthGuard
{
    public function __construct()
    {
        parent::__construct('admin');
    }
}
```

路由配置时注意：**登录接口只挂 `SetAuthGuard`，不挂 `Authentication`**：

```php
// 登录接口：只切换 guard，不要求已登录
Route::group('/admin', function () {
    Route::post('/auth/login', [AdminAuthController::class, 'login']);
})->middleware([SetAuthGuardAdmin::class]);

// 需要认证的接口：同时挂两个中间件
Route::group('/admin', function () {
    Route::get('/auth/info', [AdminAuthController::class, 'info']);
})->middleware([SetAuthGuardAdmin::class, Authentication::class]);
```

完整的多用户体系示例（含 AuthController 写法）详见 [references/multi-user-example.md](references/multi-user-example.md)。

`Authentication` 中间件会自动读取 `SetAuthGuard` 设置的 guard 名称。

---

## 控制器中获取用户

```php
use WebmanTech\Auth\Auth;

$user   = Auth::guard()->getUser();   // 获取当前用户（IdentityInterface）
$userId = Auth::guard()->getId();     // 获取用户 ID
$isGuest = Auth::guard()->isGuest();  // 是否未登录

// 指定 guard
$adminUser = Auth::guard('admin')->getUser();
```

---

## 登录 / 退出

```php
use WebmanTech\Auth\Auth;

// 登录（通常在登录接口中手动调用）
$user = User::where('email', $email)->first();
if ($user && password_verify($password, $user->password)) {
    Auth::guard()->login($user);
}

// 退出
Auth::guard()->logout();
```

---

## 扩展 Authentication 中间件

### 可选路由（登录/未登录都能访问）

```php
use WebmanTech\Auth\Middleware\Authentication;

class OptionalAuthentication extends Authentication
{
    // 这些路由认证失败时不拦截，继续执行（但如果有 token 仍会登录）
    protected function optionalRoutes(): array
    {
        return ['/api/public', 'route.name'];
    }
}
```

### 额外用户检查（如封禁检测）

```php
use WebmanTech\Auth\Middleware\Authentication;
use WebmanTech\Auth\Interfaces\IdentityInterface;
use WebmanTech\CommonUtils\Response;

class AppAuthentication extends Authentication
{
    protected function checkIdentity(IdentityInterface $identity): ?Response
    {
        /** @var User $identity */
        if ($identity->is_banned) {
            return Response::make()->withStatus(403)->withBody('账号已封禁');
        }
        return null;  // 返回 null 表示检查通过
    }
}
```

---

## 常见错误

| 错误 | 原因 | 解决 |
|------|------|------|
| `获取当前 guard 失败` | 没有当前 Request 上下文 | 确保在 HTTP 请求生命周期内调用 `Auth::guard()` |
| `not exist in auth.guards` | guard 名称不在配置中 | 检查 `auth.guards` 配置键名 |
| 认证通过但 `getUser()` 返回 null | `findIdentity` 返回了 null | 检查 `identityRepository` 的查询逻辑 |
| Session 认证刷新后丢失登录状态 | 未使用 `SessionMethod` | 改用 `SessionMethod`，它会自动处理 session 读写 |
| API 认证失败返回 302 而非 401 | 用了 `RedirectHandler` | API guard 改用 `ThrowExceptionHandler` 或 `ResponseHandler` |
