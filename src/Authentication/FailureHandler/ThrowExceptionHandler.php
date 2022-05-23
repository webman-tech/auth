<?php

namespace Kriss\WebmanAuth\Authentication\FailureHandler;

use Kriss\WebmanAuth\Exceptions\UnauthorizedException;
use Kriss\WebmanAuth\Interfaces\AuthenticationFailureHandlerInterface;
use Webman\Http\Request;
use Webman\Http\Response;

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
