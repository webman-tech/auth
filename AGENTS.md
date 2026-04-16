## 项目概述

webman 认证授权插件，提供高可扩展的认证授权功能。通过模块化设计，支持多种认证方式和用户体系，适用于复杂的多用户系统场景。

**核心功能**：
- **多用户认证**：AuthManager 管理多个 guard 实例
- **多种认证方式**：Session、请求参数、HTTP Header、HTTP Authorization、HTTP Basic、HTTP Bearer、JWT、组合认证
- **多种认证失败处理**：重定向、HTTP 401、异常、自定义
- **中间件支持**：认证和 guard 切换中间件

## 开发命令

测试、静态分析等通用命令与根项目一致，详见根目录 [AGENTS.md](../../AGENTS.md)。

## 目录结构
- `src/`：
  - `Auth.php`：门面类
  - `AuthManager.php`：认证管理器，管理多个 guard 实例
  - `Guard/`：
    - `Guard.php`：Guard 实现，负责用户认证状态管理
  - `Authentication/`：
    - `Method/`：各种认证方式（Session/Request/HttpHeader/HttpBasic/HttpBearer/HttpAuthorization/TinywanJwt/Composite）
    - `FailureHandler/`：认证失败处理策略（Redirect/Response/ThrowException）
  - `Interfaces/`：接口定义（GuardInterface/IdentityInterface/IdentityRepositoryInterface 等）
  - `Middleware/`：Authentication/SetAuthGuard 中间件
  - `Exceptions/`：UnauthorizedException
  - `Helper/`：ConfigHelper
  - `facade/`：Auth 门面
- `copy/`：配置文件模板
- `src/Install.php`：Webman 安装脚本

测试文件位于项目根目录的 `tests/Unit/Auth/`。测试环境配置和 Helper 函数详见根目录 [AGENTS.md](../../AGENTS.md) 的测试相关章节。

## 工作流程

```
HTTP 请求
    │
    ▼
AuthenticationMiddleware
    │
    ▼
AuthManager ──→ Guard
                  │
                  ▼
              Method (Session / Bearer / JWT / Composite...)
                  │
          ┌───────┴────────┐
          ▼                ▼
      认证成功          认证失败
      设置用户          FailureHandler
      继续处理          (Redirect / 401 / Exception)
```

## 代码风格

与根项目保持一致，详见根目录 [AGENTS.md](../../AGENTS.md)。

## 注意事项

1. **多用户体系**：支持同时存在多个 guard，每个 guard 独立管理用户
2. **认证方式组合**：可以组合多种认证方式
3. **异常处理**：认证失败时的处理策略可配置
4. **Guard 切换**：通过中间件可以在不同路由使用不同的 guard
