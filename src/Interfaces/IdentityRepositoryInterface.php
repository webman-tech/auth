<?php

namespace Kriss\WebmanAuth\Interfaces;

interface IdentityRepositoryInterface
{
    /**
     * @param string $id
     * @return IdentityInterface|null
     */
    public function findIdentity(string $id): ?IdentityInterface;
}
