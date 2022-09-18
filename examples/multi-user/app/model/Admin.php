<?php

namespace app\model;

use WebmanTech\Auth\Interfaces\IdentityInterface;
use WebmanTech\Auth\Interfaces\IdentityRepositoryInterface;
use support\Model;

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
    public function findIdentity(string $id): ?IdentityInterface
    {
        return static::find($id);
    }
}
