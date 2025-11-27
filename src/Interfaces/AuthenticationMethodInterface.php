<?php

namespace WebmanTech\Auth\Interfaces;

use WebmanTech\CommonUtils\Request;

/**
 * 认证方法接口
 */
interface AuthenticationMethodInterface
{
    /**
     * 认证
     * @param Request $request
     * @return IdentityInterface|null
     */
    public function authenticate(Request $request): ?IdentityInterface;
}
