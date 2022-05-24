<?php

namespace Kriss\WebmanAuth\Interfaces;

/**
 * 认证用户查询接口
 */
interface IdentityRepositoryInterface
{
    /**
     * 根据 ID 查询用户
     * @param string $id
     * @return IdentityInterface|null
     */
    public function findIdentity(string $id): ?IdentityInterface;
}
