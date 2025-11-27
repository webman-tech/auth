<?php

namespace WebmanTech\Auth\Authentication\Method;

use WebmanTech\CommonUtils\Request;

class HttpAuthorizationMethod extends BaseMethod
{
    protected string $name = 'Authorization';
    protected string $pattern = '/(.*)/';

    /**
     * @inheritDoc
     */
    protected function getCredentials(Request $request): ?string
    {
        $authorization = (string)$request->header($this->name) ?? '';
        if ($authorization && preg_match($this->pattern, $authorization, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
