<?php

namespace WebmanTech\Auth\Authentication\FailureHandler;

use WebmanTech\Auth\Exceptions\UnauthorizedException;
use WebmanTech\Auth\Interfaces\AuthenticationFailureHandlerInterface;
use Webman\Http\Request;
use Webman\Http\Response;

/**
 * 抛出异常处理器
 */
class ThrowExceptionHandler implements AuthenticationFailureHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function handle(Request $request): Response
    {
        throw new UnauthorizedException();
    }
}
