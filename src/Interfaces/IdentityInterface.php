<?php

namespace Kriss\WebmanAuth\Interfaces;

/**
 * 认证用户接口
 */
interface IdentityInterface
{
    /**
     * 获取 ID
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * 刷新用户信息
     * @return $this
     */
    public function refreshIdentity();
}
