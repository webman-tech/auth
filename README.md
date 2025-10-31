# webman-tech/auth

本项目是从 [webman-tech/components-monorepo](https://github.com/orgs/webman-tech/components-monorepo) 自动 split
出来的，请勿直接修改

## 简介

webman 认证授权插件，提供高可扩展的认证授权功能。

该插件通过模块化设计，支持多种认证方式和用户体系，解决了 webman 原生认证功能相对简单的问题，适用于复杂的多用户系统场景。

## 功能特性

- **多用户认证**：AuthManager 管理多个 guard 实例，支持多种用户体系
- **多种认证方式**：
    - Session 认证
    - 请求参数认证
    - HTTP Header 认证
    - HTTP Authorization 认证
    - HTTP Basic 认证
    - HTTP Bearer 认证
    - JWT 认证（集成 tinywan/jwt）
    - 组合认证方式
    - 自定义
- **多种认证失败处理**：
    - 重定向处理
    - HTTP 401 响应
    - 抛出异常处理
    - 自定义
- **中间件支持**：提供认证和 guard 切换中间件
- **高度可扩展**：通过接口实现自定义认证方式和处理逻辑

## 安装

```bash
composer require webman-tech/auth
```

## 快速开始

### 1. 基本配置

在 `config/plugin/webman-tech/auth/auth.php` 中配置：

```php
return [
    'default' => 'web', // 默认 guard
    'guards' => [
        'web' => [
            'class' => \WebmanTech\Auth\Guard\Guard::class,
            'identityRepository' => function() {
                return new \app\service\UserIdentityRepository();
            },
            'authenticationMethod' => function($identityRepository) {
                return new \WebmanTech\Auth\Authentication\Method\SessionMethod($identityRepository);
            },
            'authenticationFailureHandler' => function($authenticationMethod) {
                return new \WebmanTech\Auth\Authentication\FailureHandler\RedirectHandler('/login');
            },
        ],
        'api' => [
            'class' => \WebmanTech\Auth\Guard\Guard::class,
            'identityRepository' => function() {
                return new \app\service\ApiUserIdentityRepository();
            },
            'authenticationMethod' => function($identityRepository) {
                return new \WebmanTech\Auth\Authentication\Method\HttpBearerMethod($identityRepository);
            },
            'authenticationFailureHandler' => function($authenticationMethod) {
                return new \WebmanTech\Auth\Authentication\FailureHandler\ResponseHandler();
            },
        ],
    ],
];
```

### 2. 实现用户身份接口

```php
use WebmanTech\Auth\Interfaces\IdentityInterface;

class User implements IdentityInterface
{
    protected $id;
    protected $username;
    
    public function getId(): ?string
    {
        return $this->id;
    }
    
    public function refreshIdentity()
    {
        // 刷新用户信息逻辑
        return $this;
    }
    
    // 其他业务方法
}
```

### 3. 实现身份仓库接口

```php
use WebmanTech\Auth\Interfaces\IdentityRepositoryInterface;

class UserIdentityRepository implements IdentityRepositoryInterface
{
    public function findIdentity(string $token, ?string $type = null): ?IdentityInterface
    {
        // 根据 token 查找用户逻辑
        // 例如通过 session ID、JWT token 或其他凭证查找用户
        return User::find($token);
    }
}
```

### 4. 使用认证功能

```php
use WebmanTech\Auth\Auth;

// 获取默认 guard
$guard = Auth::guard();

// 获取指定 guard
$guard = Auth::guard('api');

// 用户登录
$guard->login($user);

// 用户退出
$guard->logout();

// 检查是否为游客
if ($guard->isGuest()) {
    // 未登录处理
}

// 获取当前用户
$user = $guard->getUser();

// 获取当前用户 ID
$userId = $guard->getId();
```

## 核心组件

### Auth 认证入口类

[Auth](src/Auth.php) 类是认证功能的主要入口：

- `guard()`: 获取指定名称的 guard 实例
- `getAuthManager()`: 获取认证管理器

### AuthManager 认证管理器

[AuthManager](src/AuthManager.php) 负责管理多个 guard 实例：

- `guard()`: 获取 guard 实例
- 支持单例模式，避免重复创建

### Guard 认证守卫

[Guard](src/Guard/Guard.php) 是认证的核心组件，实现 [GuardInterface](src/Interfaces/GuardInterface.php)：

- `login()`: 用户登录
- `logout()`: 用户退出
- `isGuest()`: 检查是否为游客
- `getUser()`: 获取当前用户
- `getId()`: 获取当前用户 ID
- `getAuthenticationMethod()`: 获取认证方法
- `getAuthenticationFailedHandler()`: 获取认证失败处理器

### 认证方法

所有认证方法实现 [AuthenticationMethodInterface](src/Interfaces/AuthenticationMethodInterface.php)：

- [SessionMethod](src/Authentication/Method/SessionMethod.php): Session 认证
- [RequestMethod](src/Authentication/Method/RequestMethod.php): 请求参数认证
- [HttpHeaderMethod](src/Authentication/Method/HttpHeaderMethod.php): HTTP Header 认证
- [HttpAuthorizationMethod](src/Authentication/Method/HttpAuthorizationMethod.php): HTTP Authorization 认证
- [HttpBasicMethod](src/Authentication/Method/HttpBasicMethod.php): HTTP Basic 认证
- [HttpBearerMethod](src/Authentication/Method/HttpBearerMethod.php): HTTP Bearer 认证
- [TinywanJwtMethod](src/Authentication/Method/TinywanJwtMethod.php): JWT 认证
- [CompositeMethod](src/Authentication/Method/CompositeMethod.php): 组合认证

### 认证失败处理器

所有认证失败处理器实现 [AuthenticationFailureHandlerInterface](src/Interfaces/AuthenticationFailureHandlerInterface.php)：

- [RedirectHandler](src/Authentication/FailureHandler/RedirectHandler.php): 重定向处理器
- [ResponseHandler](src/Authentication/FailureHandler/ResponseHandler.php): HTTP 响应处理器
- [ThrowExceptionHandler](src/Authentication/FailureHandler/ThrowExceptionHandler.php): 异常抛出处理器

### 身份接口

- [IdentityInterface](src/Interfaces/IdentityInterface.php): 用户身份接口
- [IdentityRepositoryInterface](src/Interfaces/IdentityRepositoryInterface.php): 身份仓库接口

## 中间件

### SetAuthGuard 设置认证守卫中间件

[SetAuthGuard](src/Middleware/SetAuthGuard.php) 用于在请求中设置当前使用的 guard：

```php
use WebmanTech\Auth\Middleware\SetAuthGuard;

// 在路由中使用
Route::group('/admin', function() {
    // 路由定义
})->middleware([
    new SetAuthGuard('admin')
]);
```

### Authentication 认证中间件

[Authentication](src/Middleware/Authentication.php) 用于验证用户身份：

```php
use WebmanTech\Auth\Middleware\Authentication;

// 全局使用
Route::group('/api', function() {
    // 路由定义
})->middleware([
    new Authentication('api') // 指定 guard
]);
```

## 扩展功能

### 自定义认证方法

实现 [AuthenticationMethodInterface](src/Interfaces/AuthenticationMethodInterface.php) 接口：

```php
use WebmanTech\Auth\Interfaces\AuthenticationMethodInterface;
use Webman\Http\Request;

class CustomMethod implements AuthenticationMethodInterface
{
    public function authenticate(Request $request): ?IdentityInterface
    {
        // 自定义认证逻辑
        // 从请求中提取凭证并验证用户身份
        return $identity; // 返回 IdentityInterface 实例或 null
    }
}
```

### 自定义认证失败处理器

实现 [AuthenticationFailureHandlerInterface](src/Interfaces/AuthenticationFailureHandlerInterface.php) 接口：

```php
use WebmanTech\Auth\Interfaces\AuthenticationFailureHandlerInterface;
use Webman\Http\Request;
use Webman\Http\Response;

class CustomHandler implements AuthenticationFailureHandlerInterface
{
    public function handle(Request $request): Response
    {
        // 自定义认证失败处理逻辑
        return new Response(); // 返回响应
    }
}
```

### 自定义 Guard

继承 [Guard](src/Guard/Guard.php) 类或实现 [GuardInterface](src/Interfaces/GuardInterface.php) 接口：

```php
use WebmanTech\Auth\Guard\Guard;

class CustomGuard extends Guard
{
    // 重写或扩展 Guard 功能
}
```

## 最佳实践

1. **合理设计用户体系**：根据业务需求设计合适的用户模型和认证方式
2. **选择合适的认证方法**：Web 应用使用 Session，API 使用 Token
3. **安全考虑**：敏感操作需要重新验证用户身份
4. **异常处理**：合理处理认证异常，提供友好的用户体验
5. **性能优化**：避免重复查询用户信息，合理使用缓存
6. **日志记录**：记录重要的认证操作，便于审计和问题排查