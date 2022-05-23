<?php

namespace Kriss\WebmanAuth\Interfaces;

interface IdentityInterface
{
    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @return $this
     */
    public function refreshIdentity();
}
