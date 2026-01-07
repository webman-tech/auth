# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## 项目概述

webman 认证授权插件，提供高可扩展的认证授权功能。通过模块化设计，支持多种认证方式和用户体系，适用于复杂的多用户系统场景。

**核心功能**：
- **多用户认证**：AuthManager 管理多个 guard 实例
- **多种认证方式**：Session、请求参数、HTTP Header、HTTP Authorization、HTTP Basic、HTTP Bearer、JWT、组合认证
- **多种认证失败处理**：重定向、HTTP 401、异常、自定义
- **中间件支持**：认证和 guard 切换中间件

## 开发命令

测试、静态分析等通用命令与根项目一致，详见根目录 [CLAUDE.md](../../CLAUDE.md)。

## 项目架构

### 核心组件
- **AuthenticationManager**：认证管理器，管理多个 guard
- **Authentication**：
  - `Method`：各种认证方式实现（Session、HTTP、JWT、组合等）
  - `Exception`：认证异常处理
- **Guard**：
  - `Guard`：Guard 接口和实现
  - `GenericGuard`：通用 Guard 实现
  - `SessionGuard`：Session Guard 实现
- **Middleware**：
  - `AuthenticationMiddleware`：认证中间件
  - `SetAuthGuardMiddleware`：设置 Guard 中间件

### 目录结构
- `src/`：
  - `Authentication/`：认证相关
  - `Guard/`：Guard 相关
  - `Middleware/`：中间件
  - `Contracts/`：接口定义
  - `Support/`：支持类
  - `Traits/`：trait 类
- `copy/`：配置文件模板
- `src/Install.php`：Webman 安装脚本

测试文件位于项目根目录的 `tests/Unit/Auth/`。

## 代码风格

与根项目保持一致，详见根目录 [CLAUDE.md](../../CLAUDE.md)。

## 注意事项

1. **多用户体系**：支持同时存在多个 guard，每个 guard 独立管理用户
2. **认证方式组合**：可以组合多种认证方式
3. **异常处理**：认证失败时的处理策略可配置
4. **Guard 切换**：通过中间件可以在不同路由使用不同的 guard
5. **测试位置**：单元测试在项目根目录的 `tests/Unit/Auth/` 下，而非包内
