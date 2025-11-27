<?php

namespace WebmanTech\Auth\Authentication\Method;

use WebmanTech\CommonUtils\Request;

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
