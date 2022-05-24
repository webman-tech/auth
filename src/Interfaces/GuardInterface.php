<?php

namespace Kriss\WebmanAuth\Interfaces;

/**
 * Guard 接口
 */
interface GuardInterface
{
    /**
     * 获取授权方法
     * @return AuthenticationMethodInterface
     */
    public function getAuthenticationMethod(): AuthenticationMethodInterface;

    /**
     * 获取认证失败处理器
     * @return AuthenticationFailureHandlerInterface
     */
    public function getAuthenticationFailedHandler(): AuthenticationFailureHandlerInterface;

    /**
     * 登录
     * @param IdentityInterface $identity
     */
    public function login(IdentityInterface $identity): void;

    /**
     * 退出登录
     */
    public function logout(): void;

    /**
     * 是否未登录
     * @return bool
     */
    public function isGuest(): bool;

    /**
     * 获取当前登录用户
     * @param bool $refresh
     * @return IdentityInterface|null
     */
    public function getUser(bool $refresh = false): ?IdentityInterface;

    /**
     * 获取当前登录用户 ID
     * @return string|null
     */
    public function getId(): ?string;
}
