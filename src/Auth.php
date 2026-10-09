<?php

namespace WebmanTech\Auth;

use WebmanTech\Auth\Interfaces\GuardInterface;
use WebmanTech\Auth\Middleware\SetAuthGuard;
use WebmanTech\CommonUtils\Request;

class Auth
{
    const REQUEST_AUTH_MANAGER = 'auth_manager';

    /**
     * guard
     * 当使用 Middleware/SetAuthGuard 后，可以获取到当前的 Guard
     * @param string|null $name
     * @return GuardInterface
     */
    public static function guard(?string $name = null): GuardInterface
    {
        $request = Request::getCurrent();
        if ($request !== null && ($authManager = self::resolveAuthManager($request))) {
            $name = $name ?: $request->getCustomData(SetAuthGuard::REQUEST_GUARD_NAME);
            return $authManager->guard($name);
        }
        throw new \InvalidArgumentException('获取当前 guard 失败，请确认配置');
    }

    /**
     * @return AuthManager|null
     */
    public static function getAuthManager(): ?AuthManager
    {
        return self::resolveAuthManager(Request::getCurrent());
    }

    /**
     * 从请求上解析（或初始化）AuthManager
     */
    private static function resolveAuthManager(?Request $request): ?AuthManager
    {
        if ($request === null) {
            return null;
        }
        $value = $request->getCustomData(static::REQUEST_AUTH_MANAGER);
        if (!$value) {
            $value = new AuthManager();
            $request->withCustomData([
                static::REQUEST_AUTH_MANAGER => $value,
            ]);
        }
        return $value;
    }
}
