<?php

namespace app\model;

use support\Model;
use WebmanTech\Auth\Interfaces\IdentityInterface;
use WebmanTech\Auth\Interfaces\IdentityRepositoryInterface;

class Admin extends Model implements IdentityInterface, IdentityRepositoryInterface
{
    // 其他方法

    /**
     * @inheritDoc
     */
    public function getId(): ?string
    {
        return $this->{$this->primaryKey};
    }

    /**
     * @inheritDoc
     */
    public function refreshIdentity()
    {
        return $this->refresh();
    }

    /**
     * @inheritDoc
     */
    public function findIdentity(string $token, string $type = null): ?IdentityInterface
    {
        return static::find($token);
    }

    public function validatePassword(string $password): bool
    {
        return true;
    }
}
