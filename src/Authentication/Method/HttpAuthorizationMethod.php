<?php

namespace WebmanTech\Auth\Authentication\Method;

use Webman\Http\Request;

class HttpAuthorizationMethod extends BaseMethod
{
    protected string $name = 'Authorization';
    protected string $pattern = '/(.*)/';

    /**
     * @inheritDoc
     */
    protected function getCredentials(Request $request): ?string
    {
        $authorization = $request->header($this->name);
        if (preg_match($this->pattern, $authorization, $matches)) {
            return $matches[1];
        }

        return null;
    }
}