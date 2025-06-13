<?php

namespace WebmanTech\Auth\Interfaces;

/**
 * 认证用户查询接口
 */
interface IdentityRepositoryInterface
{
    /**
     * 根据 token 查询用户
     * @param string $token token 或 id
     * @param string|null $type token 类型
     * @return IdentityInterface|null
     */
    public function findIdentity(string $token, ?string $type = null): ?IdentityInterface;
}
