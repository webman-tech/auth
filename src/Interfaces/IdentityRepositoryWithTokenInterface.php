<?php

namespace Kriss\WebmanAuth\Interfaces;

/**
 * 认证用户查询接口
 */
interface IdentityRepositoryWithTokenInterface
{
    /**
     * 根据 token 查询用户
     * @param string $token
     * @param string|null $type
     * @return IdentityInterface|null
     */
    public function findIdentityByToken(string $token, string $type = null): ?IdentityInterface;
}
