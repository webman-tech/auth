<?php

namespace WebmanTech\Auth\Authentication\FailureHandler;

use WebmanTech\Auth\Interfaces\AuthenticationFailureHandlerInterface;
use WebmanTech\CommonUtils\Request;
use WebmanTech\CommonUtils\Response;

/**
 * 重定向处理器
 */
class RedirectHandler implements AuthenticationFailureHandlerInterface
{
    public function __construct(protected string $redirectUrl)
    {
    }

    /**
     * @inheritDoc
     */
    public function handle(Request $request): Response
    {
        return Response::make()
            ->withStatus(302)
            ->withHeaders(['Location' => $this->redirectUrl]);
    }
}
