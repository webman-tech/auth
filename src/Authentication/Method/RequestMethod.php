<?php

namespace Kriss\WebmanAuth\Authentication\Method;

use Webman\Http\Request;

/**
 * 请求参数方式
 */
class RequestMethod extends BaseMethod
{
    protected string $name = 'access-token';
    protected string $requestMethod = 'input';

    /**
     * @inheritDoc
     */
    protected function getCredentials(Request $request): ?string
    {
        return $request->{$this->requestMethod}($this->name);
    }
}
