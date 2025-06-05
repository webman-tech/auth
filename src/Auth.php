<?php

namespace WebmanTech\Auth;

use WebmanTech\Auth\Interfaces\GuardInterface;
use WebmanTech\Auth\Middleware\SetAuthGuard;

class Auth
{
    const REQUEST_AUTH_MANAGER = 'auth_manager';

    /**
     * guard
     * 当使用 Middleware/SetAuthGuard 后，可以获取到当前的 Guard
     * @param string|null $name
     * @return GuardInterface
     */
    public static function guard(string $name = null): GuardInterface
    {
        if ($authManager = static::getAuthManager()) {
            /* @phpstan-ignore-next-line */
            $name = $name ?: request()->{SetAuthGuard::REQUEST_GUARD_NAME};
            return $authManager->guard($name);
        }
        throw new \InvalidArgumentException('获取当前 guard 失败，请确认配置');
    }

    /**
     * @return AuthManager|null
     */
    public static function getAuthManager(): ?AuthManager
    {
        $request = request();
        if (!$request) {
            return null;
        }
        if (!$request->{static::REQUEST_AUTH_MANAGER}) {
            $request->{static::REQUEST_AUTH_MANAGER} = new AuthManager();
        }
        return $request->{static::REQUEST_AUTH_MANAGER};
    }
}
