# webman-tech/auth

本项目是从 [webman-tech/components-monorepo](https://github.com/orgs/webman-tech/components-monorepo) 自动 split 出来的，请勿直接修改

> 简介

Auth plugin for webman

提供高可扩展的认证授权插件，包含以下功能：

- [x] 多用户认证: AuthManager 管理多 guard 实例
- [x] 多认证方式
  - [x] SessionMethod: session 认证
  - [x] RequestMethod: 请求参数认证，token 放在 query 或 post 中
  - [x] HttpHeaderMethod: 请求 Header 认证，token 放在 header 中
  - [x] HttpAuthorizationMethod: 请求 Header 中的 Authorization 认证
  - [x] HttpBasicMethod: 请求 Basic 认证
  - [x] HttpBearerMethod: 请求 Bearer 认证
  - [x] TinywanJwtMethod: 使用 [tinywan/jwt](https://github.com/Tinywan/webman-jwt) 进行 jwt 认证
  - [x] CompositeMethod: 组合以上多种认证方式
- [x] 多认证失败处理器
  - [x] RedirectHandler: 重定向处理器
  - [x] ResponseHandler: 响应 401 http status
  - [x] ThrowExceptionHandler: 抛出 UnauthorizedException 异常
    
## 安装

```bash
composer require webman-tech/auth
```

## 配置

详见： [auth.php](copy/config/plugin/auth.php)

## 使用

### 认证授权方法

```php
use WebmanTech\Auth\Auth;

$guard = Auth::guard(); // 获取默认的 guard
$guard = Auth::guard('admin'); // 获取指定名称的 guard

$guard->login($user); // 用户登录
$guard->logout(); // 用户退出登录
$guard->getId(); // 获取当前登录用户的 id
$guard->getUser(); // 获取当前登录用户的实例
$guard->isGuest(); // 判断当前用户是否为游客
```

其他方法详见: [`GuardInterface`](src/Interfaces/GuardInterface.php)

### 中间件

- 全局切换 Guard: `WebmanTech\Auth\Middleware\SetAuthGuard`
- 认证授权: `WebmanTech\Auth\Middleware\Authentication`

## 扩展

支持扩展以下接口：

- 认证方式接口：[`AuthenticationMethodInterface`](src/Interfaces/AuthenticationMethodInterface.php)
- 认证失败处理方式接口：[`AuthenticationFailureHandlerInterface`](src/Interfaces/AuthenticationFailureHandlerInterface.php)
- Guard 接口：[`GuardInterface`](src/Interfaces/GuardInterface.php)

## 例子

多种用户体系（前端用户api接口，后端用户session）

详见 [examples/multi-user](examples/multi-user)