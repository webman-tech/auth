<?php

namespace WebmanTech\Auth\Authentication\Method;

/**
 * Http Bearer 认证
 */
class HttpBearerMethod extends HttpAuthorizationMethod
{
    protected string $pattern = '/Bearer\s+(.*)$/i';
}
