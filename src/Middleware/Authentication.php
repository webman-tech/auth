<?php

namespace Kriss\WebmanAuth\Middleware;

use Kriss\WebmanAuth\facade\Auth;
use Kriss\WebmanAuth\Interfaces\GuardInterface;
use Kriss\WebmanAuth\Interfaces\IdentityInterface;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 * 授权认证中间件
 */
abstract class Authentication implements MiddlewareInterface
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
        $path = $request->path();
        if (in_array($path, $this->optionalRoutes())) {
            return true;
        }

        return false;
    }

    /**
     * 当挂载了中间件，但是验证不通过时，此处配置的路由将会继续执行
     * 用于某些路由既可以登录也可以不登录访问，如果登录了需要获取用户信息的情况
     * @return array
     */
    protected function optionalRoutes(): array
    {
        return [];
    }
}
