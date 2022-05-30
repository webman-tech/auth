<?php

namespace Kriss\WebmanAuth;

use Kriss\WebmanAuth\Interfaces\GuardInterface;
use Kriss\WebmanAuth\Middleware\SetAuthGuard;

class Auth
{
    const REQUEST_AUTH_MANAGER = 'auth_manager';

    /**
     * guard
     * 当使用 Middleware/SetAuthGuard 后，可以获取到当前的 Guard
     * @param string|null $name
     * @return GuardInterface|null
     */
    public static function guard(string $name = null): ?GuardInterface
    {
        if ($authManager = static::getAuthManager()) {
            $name = $name ?: request()->{SetAuthGuard::REQUEST_GUARD_NAME};
            return $authManager->guard($name);
        }
        return null;
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