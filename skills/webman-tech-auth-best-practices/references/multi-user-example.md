# 多用户体系完整示例

## 场景

同一项目中同时存在 API 用户（token 认证）和 Admin 用户（session 认证）。

## 用户模型

```php
// app/model/User.php — API 用户（token 认证）
class User extends Model implements IdentityInterface, IdentityRepositoryInterface
{
    public function getId(): ?string
    {
        return (string) $this->id;
    }

    public function refreshIdentity(): static
    {
        return $this->refresh();
    }

    public function findIdentity(string $token, ?string $type = null): ?IdentityInterface
    {
        return static::query()->where('access_token', $token)->first();
    }

    // 刷新 token（登录时调用）
    public function refreshToken(?string $token = null): void
    {
        $this->access_token = $token ?? \Illuminate\Support\Str::random(32);
        $this->save();
    }
}

// app/model/Admin.php — Admin 用户（session 认证）
class Admin extends Model implements IdentityInterface, IdentityRepositoryInterface
{
    public function getId(): ?string
    {
        return (string) $this->id;
    }

    public function refreshIdentity(): static
    {
        return $this->refresh();
    }

    public function findIdentity(string $token, ?string $type = null): ?IdentityInterface
    {
        // session 认证时 token 是用户 id
        return static::query()->find($token);
    }
}
```

## Guard 配置

```php
// config/plugin/webman-tech/auth/auth.php
return [
    'default' => 'user',
    'guards' => [
        'user' => [
            'identityRepository' => fn() => new app\model\User(),
            'authenticationMethod' => fn($repo) => new HttpBearerMethod($repo),
            'authenticationFailureHandler' => fn() => new ThrowExceptionHandler(),
        ],
        'admin' => [
            'identityRepository' => fn() => new app\model\Admin(),
            'authenticationMethod' => fn($repo) => new SessionMethod($repo),
            'authenticationFailureHandler' => fn() => new RedirectHandler(route('admin.login')),
        ],
    ],
];
```

## SetAuthGuard 中间件包装

**webman 中间件不支持带参数构造**，需要为每个 guard 创建一个无参数的子类：

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

## 路由配置

```php
// config/route.php

// Admin 路由
// 登录接口：只挂 SetAuthGuard，不挂 Authentication（未登录也能访问）
Route::group('/admin', function () {
    Route::post('/auth/login', [AdminAuthController::class, 'login']);
})->middleware([SetAuthGuardAdmin::class]);

// 需要认证的 Admin 路由：同时挂 SetAuthGuard + Authentication
Route::group('/admin', function () {
    Route::get('/auth/info', [AdminAuthController::class, 'info']);
    Route::post('/auth/logout', [AdminAuthController::class, 'logout']);
})->middleware([SetAuthGuardAdmin::class, Authentication::class]);

// API 路由（默认 guard 是 user，无需 SetAuthGuard）
Route::group('/api', function () {
    Route::post('/auth/login', [ApiAuthController::class, 'login']);
});
Route::group('/api', function () {
    Route::get('/auth/info', [ApiAuthController::class, 'info']);
    Route::post('/auth/logout', [ApiAuthController::class, 'logout']);
})->middleware([Authentication::class]);
```

## AuthController 标准写法

```php
// API 用户登录（token 认证）
class ApiAuthController
{
    public function login(Request $request): Response
    {
        $user = User::query()->where('username', $request->post('username'))->first();
        if (!$user || !$user->validatePassword($request->post('password'))) {
            throw new \InvalidArgumentException('帐号或密码错误');
        }
        Auth::guard()->login($user);
        $user->refreshToken();  // 生成新 token
        return json($user);
    }

    public function logout(): Response
    {
        if (!Auth::guard()->isGuest()) {
            /** @var User $user */
            $user = Auth::guard()->getUser();
            $user->refreshToken(null);  // 清除 token
            Auth::guard()->logout();
        }
        return json('ok');
    }

    public function info(): Response
    {
        return json(Auth::guard()->getUser());
    }
}

// Admin 用户登录（session 认证）
class AdminAuthController
{
    public function login(Request $request): Response
    {
        $admin = Admin::query()->where('username', $request->post('username'))->first();
        if (!$admin || !$admin->validatePassword($request->post('password'))) {
            throw new \InvalidArgumentException('帐号或密码错误');
        }
        Auth::guard()->login($admin);  // session 自动保存
        return json($admin);
    }

    public function logout(): Response
    {
        Auth::guard()->logout();  // session 自动清除
        return json('ok');
    }

    public function info(): Response
    {
        return json(Auth::guard()->getUser());
    }
}
```
