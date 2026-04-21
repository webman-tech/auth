# webman-tech/auth

本项目是从 [webman-tech/components-monorepo](https://github.com/orgs/webman-tech/components-monorepo) 自动 split 出来的，请勿直接修改

## 简介

webman 认证授权插件，提供高可扩展的认证授权功能。

该插件通过模块化设计，支持多种认证方式和用户体系，解决了 webman 原生认证功能相对简单的问题，适用于复杂的多用户系统场景。

## 功能特性

- **多用户认证**：AuthManager 管理多个 guard 实例，支持多种用户体系
- **多种认证方式**：Session、请求参数、HTTP Header、HTTP Authorization、HTTP Basic、HTTP Bearer、JWT（集成 tinywan/jwt）、组合认证及自定义
- **多种认证失败处理**：重定向、HTTP 401 响应、抛出异常及自定义
- **中间件支持**：提供认证和 guard 切换中间件
- **高度可扩展**：通过接口实现自定义认证方式和处理逻辑

## 安装

```bash
composer require webman-tech/auth
```

## 核心组件

### Auth 认证入口类

[Auth](src/Auth.php) 是认证功能的主要入口，通过 `guard()` 获取指定名称的 guard 实例，通过 `getAuthManager()` 获取认证管理器。

### AuthManager 认证管理器

[AuthManager](src/AuthManager.php) 负责管理多个 guard 实例，支持单例模式避免重复创建。

### Guard 认证守卫

[Guard](src/Guard/Guard.php) 是认证的核心组件，实现 [GuardInterface](src/Interfaces/GuardInterface.php)，提供用户登录（`login()`）、退出（`logout()`）、游客检查（`isGuest()`）、获取当前用户（`getUser()`）及用户 ID（`getId()`）等操作。

### 认证方法

所有认证方法实现 [AuthenticationMethodInterface](src/Interfaces/AuthenticationMethodInterface.php)，内置实现包括：

- [SessionMethod](src/Authentication/Method/SessionMethod.php)：Session 认证
- [RequestMethod](src/Authentication/Method/RequestMethod.php)：请求参数认证
- [HttpHeaderMethod](src/Authentication/Method/HttpHeaderMethod.php)：HTTP Header 认证
- [HttpAuthorizationMethod](src/Authentication/Method/HttpAuthorizationMethod.php)：HTTP Authorization 认证
- [HttpBasicMethod](src/Authentication/Method/HttpBasicMethod.php)：HTTP Basic 认证
- [HttpBearerMethod](src/Authentication/Method/HttpBearerMethod.php)：HTTP Bearer 认证
- [TinywanJwtMethod](src/Authentication/Method/TinywanJwtMethod.php)：JWT 认证
- [CompositeMethod](src/Authentication/Method/CompositeMethod.php)：组合认证

### 认证失败处理器

所有认证失败处理器实现 [AuthenticationFailureHandlerInterface](src/Interfaces/AuthenticationFailureHandlerInterface.php)，内置实现包括：

- [RedirectHandler](src/Authentication/FailureHandler/RedirectHandler.php)：重定向处理器
- [ResponseHandler](src/Authentication/FailureHandler/ResponseHandler.php)：HTTP 响应处理器
- [ThrowExceptionHandler](src/Authentication/FailureHandler/ThrowExceptionHandler.php)：异常抛出处理器

### 身份接口

- [IdentityInterface](src/Interfaces/IdentityInterface.php)：用户身份接口，需实现 `getId()` 和 `refreshIdentity()`
- [IdentityRepositoryInterface](src/Interfaces/IdentityRepositoryInterface.php)：身份仓库接口，需实现 `findIdentity()` 根据 token 查找用户

## 中间件

- [SetAuthGuard](src/Middleware/SetAuthGuard.php)：在请求中设置当前使用的 guard，通常通过继承该类创建无参子类后在路由中使用
- [Authentication](src/Middleware/Authentication.php)：验证用户身份，配合 `SetAuthGuard` 使用

## 扩展

可通过实现对应接口来自定义认证方法（`AuthenticationMethodInterface`）、认证失败处理器（`AuthenticationFailureHandlerInterface`），或继承 `Guard` 类扩展守卫行为。

## AI 辅助

- **开发维护**：[AGENTS.md](AGENTS.md) — 面向 AI 的代码结构和开发规范说明
- **使用指南**：[skills/webman-tech-auth-best-practices/SKILL.md](skills/webman-tech-auth-best-practices/SKILL.md) — 面向 AI 的最佳实践，可安装到 Claude Code 的 skills 目录使用
