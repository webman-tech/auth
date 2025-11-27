<?php

namespace WebmanTech\Auth\Authentication\FailureHandler;

use WebmanTech\Auth\Interfaces\AuthenticationFailureHandlerInterface;
use WebmanTech\CommonUtils\Request;
use WebmanTech\CommonUtils\Response;

/**
 * Response 返回内容处理器
 */
class ResponseHandler implements AuthenticationFailureHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function handle(Request $request): Response
    {
        return Response::make()->withStatus(401);
    }
}
