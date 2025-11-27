<?php

namespace WebmanTech\Auth\Authentication\Method;

use WebmanTech\CommonUtils\Request;

/**
 * 请求参数方式
 */
class RequestMethod extends BaseMethod
{
    protected string $name = 'access-token';
    protected string $requestMethod = 'input'; // get/post/header/cookie

    /**
     * @inheritDoc
     */
    protected function getCredentials(Request $request): ?string
    {
        $methodsMap = [
            'input' => 'post',
        ];
        $method = $methodsMap[$this->requestMethod] ?? $this->requestMethod;

        return $request->{$method}($this->name);
    }
}
