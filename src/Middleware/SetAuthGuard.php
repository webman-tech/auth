<?php

namespace WebmanTech\Auth\Middleware;

use WebmanTech\CommonUtils\Middleware\BaseMiddleware;
use WebmanTech\CommonUtils\Request;
use WebmanTech\CommonUtils\Response;

/**
 * 设置路由下的当前 auth 的 guardName
 */
class SetAuthGuard extends BaseMiddleware
{
    public const REQUEST_GUARD_NAME = 'auth_current_guard_name';

    public function __construct(protected ?string $guardName = null)
    {
    }

    public static function setGuardName(Request $request, ?string $guardName = null): void
    {
        if ($guardName) {
            $request->withCustomData([
                static::REQUEST_GUARD_NAME => $guardName
            ]);
        }
    }

    /**
     * @inheritDoc
     */
    public function processRequest(Request $request, \Closure $handler): Response
    {
        static::setGuardName($request, $this->guardName);

        return $handler($request);
    }
}
