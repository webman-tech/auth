<?php

namespace WebmanTech\Auth\Middleware;

use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 * 设置路由下的当前 auth 的 guardName
 */
class SetAuthGuard implements MiddlewareInterface
{
    public const REQUEST_GUARD_NAME = 'auth_current_guard_name';

    public function __construct(protected ?string $guardName = null)
    {
    }

    public static function setGuardName(Request $request, ?string $guardName = null): void
    {
        if ($guardName) {
            $request->{static::REQUEST_GUARD_NAME} = $guardName;
        }
    }

    /**
     * @inheritDoc
     */
    public function process(Request $request, callable $handler): Response
    {
        static::setGuardName($request, $this->guardName);

        return $handler($request);
    }
}
