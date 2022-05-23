<?php

namespace Kriss\WebmanAuth\Authentication\FailureHandler;

use Kriss\WebmanAuth\Interfaces\AuthenticationFailureHandlerInterface;
use Webman\Http\Request;
use Webman\Http\Response;

class RedirectHandler implements AuthenticationFailureHandlerInterface
{
    protected string $redirectUrl;

    public function __construct(string $redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
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
