<?php

namespace Kriss\WebmanAuth\Interfaces;

interface GuardInterface
{
    /**
     * @return AuthenticationMethodInterface
     */
    public function getAuthenticationMethod(): AuthenticationMethodInterface;

    /**
     * @return AuthenticationFailureHandlerInterface
     */
    public function getAuthenticationFailedHandler(): AuthenticationFailureHandlerInterface;

    /**
     * @param IdentityInterface $identity
     */
    public function login(IdentityInterface $identity): void;

    /**
     * @return bool
     */
    public function isGuest(): bool;

    /**
     * @param bool $refresh
     * @return IdentityInterface|null
     */
    public function getUser(bool $refresh = false): ?IdentityInterface;

    /**
     * @return string|null
     */
    public function getId(): ?string;
}
