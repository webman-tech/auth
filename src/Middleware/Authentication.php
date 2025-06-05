<?php

namespace WebmanTech\Auth\Middleware;

use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;
use Webman\Route\Route;
use WebmanTech\Auth\Auth;
use WebmanTech\Auth\Interfaces\GuardInterface;
use WebmanTech\Auth\Interfaces\IdentityInterface;

/**
 * 授权认证中间件
 */
class Authentication implements MiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function process(Request $request, callable $handler): Response
    {
        $guard = $this->getGuard();
        $identity = $guard->getAuthenticationMethod()->authenticate($request);
        if ($identity instanceof IdentityInterface) {
            $guard->login($identity);
            return $handler($request);
        }
        if ($this->isOptionalRoute($request)) {
            return $handler($request);
        }

        return $guard->getAuthenticationFailedHandler()->handle($request);
    }

    /**
     * guard
     * @return GuardInterface
     */
    protected function getGuard(): GuardInterface
    {
        return Auth::guard();
    }

    /**
     * 是否是可选的路由
     * @param Request $request
     * @return bool
     */
    protected function isOptionalRoute(Request $request): bool
    {
        /* @phpstan-ignore-next-line */
        if ($request->route instanceof Route) {
            $name = $request->route->getName();
            if (in_array($name, $this->optionalRoutes())) {
                return true;
            }
        }
        $path = $request->path();
        if (in_array($path, $this->optionalRoutes())) {
            return true;
        }

        return false;
    }

    /**
     * 当挂载了中间件，但是验证不通过时，此处配置的路由将会继续执行
     * 用于某些路由既可以登录也可以不登录访问，如果登录了需要获取用户信息的情况
     * 支出路由的 name 和 path
     * @return array
     */
    protected function optionalRoutes(): array
    {
        return [];
    }
}
