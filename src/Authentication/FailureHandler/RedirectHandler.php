<?php

namespace WebmanTech\Auth\Authentication\FailureHandler;

use WebmanTech\Auth\Interfaces\AuthenticationFailureHandlerInterface;
use Webman\Http\Request;
use Webman\Http\Response;

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
        return (new Response())
            ->withStatus(302)
            ->withHeader('Location', $this->redirectUrl);
    }
}
