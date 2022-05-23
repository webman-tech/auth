<?php

namespace Kriss\WebmanAuth\Middleware;

use Kriss\WebmanAuth\Interfaces\GuardInterface;
use Kriss\WebmanAuth\Interfaces\IdentityInterface;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

abstract class Authentication implements MiddlewareInterface
{
    /**
     * Auth::guard()
     * @return GuardInterface
     */
    abstract protected function getGuard(): GuardInterface;

    /**
     * @return array
     */
    abstract protected function exceptRoutes(): array;

    /**
     * @inheritDoc
     */
    public function process(Request $request, callable $handler): Response
    {
        $guard = $this->getGuard();
        $identity = $guard->getAuthenticationMethod()->authenticate($request);
        if ($identity instanceof IdentityInterface) {
            $guard->login($identity);
        } elseif (!$this->isExceptRoute($request)) {
            return $guard->getAuthenticationFailedHandler()->handle($request);
        }

        return $handler($request);
    }

    protected function isExceptRoute(Request $request): bool
    {
        if ($request->path() === '/admin/auth/login') {
            return true;
        }
        return false;
    }
}
