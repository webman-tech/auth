<?php

namespace WebmanTech\Auth\Authentication\Method;

use Webman\Http\Request;

/**
 * 请求头方式
 */
class HttpHeaderMethod extends BaseMethod
{
    protected string $name = 'X-Api-Key';

    /**
     * @inheritDoc
     */
    protected function getCredentials(Request $request): ?string
    {
        return $request->header($this->name);
    }
}
