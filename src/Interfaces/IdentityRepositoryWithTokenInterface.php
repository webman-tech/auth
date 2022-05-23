<?php

namespace Kriss\WebmanAuth\Interfaces;

interface IdentityRepositoryWithTokenInterface
{
    /**
     * @param string $token
     * @param string|null $type
     * @return IdentityInterface|null
     */
    public function findIdentityByToken(string $token, string $type = null): ?IdentityInterface;
}
