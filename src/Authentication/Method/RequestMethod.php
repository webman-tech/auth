<?php

namespace WebmanTech\Auth\Authentication\Method;

use WebmanTech\CommonUtils\Request;

/**
 * 请求参数方式
 */
class RequestMethod extends BaseMethod
{
    /**
     * requestMethod 别名映射（input 等价于 post，从请求数据中取值）
     */
    protected const METHOD_MAP = [
        'input' => 'post',
    ];

    protected string $name = 'access-token';
    protected string $requestMethod = 'input'; // get/post/header/cookie

    /**
     * @inheritDoc
     */
    protected function getCredentials(Request $request): ?string
    {
        $method = self::METHOD_MAP[$this->requestMethod] ?? $this->requestMethod;

        return $request->{$method}($this->name);
    }
}
