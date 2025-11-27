<?php

namespace WebmanTech\Auth\Middleware;

use WebmanTech\Auth\Auth;
use WebmanTech\Auth\Interfaces\GuardInterface;
use WebmanTech\Auth\Interfaces\IdentityInterface;
use WebmanTech\CommonUtils\Middleware\BaseMiddleware;
use WebmanTech\CommonUtils\Request;
use WebmanTech\CommonUtils\Response;

/**
 * 授权认证中间件
 */
class Authentication extends BaseMiddleware
{
    public function __construct(protected ?string $guardName = null)
    {
    }

    /**
     * @inheritDoc
     */
    public function processRequest(Request $request, \Closure $handler): Response
    {
        if ($this->guardName !== null) {
            SetAuthGuard::setGuardName($request, $this->guardName);
        }

        $guard = $this->getGuard();
        $identity = $guard->getAuthenticationMethod()->authenticate($request);
        if ($identity instanceof IdentityInterface) {
            $guard->login($identity);
            $result = $this->checkIdentity($identity);
            if ($result instanceof Response) {
                return $result;
            }
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
        $checked = [$request->getPath()];
        if ($route = $request->getRoute()) {
            if ($name = $route->getName()) {
                $checked[] = $name;
            }
            if ($path = $route->getPath()) {
                $checked[] = $path;
            }
        }
        foreach (array_unique($checked) as $item) {
            if (in_array($item, $this->optionalRoutes())) {
                return true;
            }
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

    /**
     * 预留出来可以用来做用户信息检查
     * @param IdentityInterface $identity
     * @return Response|null
     */
    protected function checkIdentity(IdentityInterface $identity): ?Response
    {
        return null;
    }
}
