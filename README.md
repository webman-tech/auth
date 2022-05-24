# kriss/webman-auth

Auth plugin for webman

## 简介

提供高可扩展的认证授权插件，包含以下功能：

- [x] 多用户认证: AuthManager 管理多 guard 实例
- [x] 多认证方式
  - [x] SessionMethod: session 认证
  - [x] RequestMethod: 请求参数认证，token 放在 query 或 post 中
  - [x] RequestHeaderMethod: 请求 Header 认证，token 放在 header 中
  - [x] HttpBasicMethod: 请求 Basic 认证
  - [x] HttpBearerMethod: 请求 Bearer 认证
- [x] 多认证失败处理器
  - [x] RedirectHandler: 重定向处理器
  - [x] ResponseHandler: 响应 401 http status
  - [x] ThrowExceptionHandler: 抛出 UnauthorizedException 异常
    
## 安装

```bash
composer require kriss/webman-auth
```

## 配置

详见： [auth.php](src/config/plugin/kriss/webman-auth/auth.php)

## 使用

### 认证授权方法

```php
use Kriss\WebmanAuth\facade\Auth;

$guard = Auth::guard(); // 获取默认的 guard
$guard = Auth::guard('admin'); // 获取指定名称的 guard

$guard->login($user); // 用户登录
$guard->logout(); // 用户退出登录
$guard->getId(); // 获取当前登录用户的 id
$guard->getUser(); // 获取当前登录用户的实例
$guard->isGuest(); // 判断当前用户是否为游客
```

其他方法详见: [`GuardInterface`](src/Interfaces/GuardInterface.php)

### 认证授权中间件

`Kriss\WebmanAuth\Middleware\Authentication`

## 扩展

支持扩展以下接口：

- 认证方式接口：[`AuthenticationMethodInterface`](src/Interfaces/AuthenticationMethodInterface.php)
- 认证失败处理方式接口：[`AuthenticationFailureHandlerInterface`](src/Interfaces/AuthenticationFailureHandlerInterface.php)
- Guard 接口：[`GuardInterface`](src/Interfaces/GuardInterface.php)

## 例子

多种用户体系（前端用户api接口，后端用户session）

详见 [examples/multi-user](examples/multi-user)