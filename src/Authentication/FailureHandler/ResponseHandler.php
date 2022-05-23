<?php

namespace Kriss\WebmanAuth\Authentication\FailureHandler;

use Kriss\WebmanAuth\Interfaces\AuthenticationFailureHandlerInterface;
use Webman\Http\Request;
use Webman\Http\Response;

class ResponseHandler implements AuthenticationFailureHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function handle(Request $request): Response
    {
        return (new Response())->withStatus(401);
    }
}
