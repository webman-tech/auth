<?php

namespace Kriss\WebmanAuth\Authentication\Method;

use Kriss\WebmanAuth\Guard\Guard;
use Kriss\WebmanAuth\Interfaces\AuthenticationMethodInterface;
use Kriss\WebmanAuth\Interfaces\IdentityInterface;
use Kriss\WebmanAuth\Interfaces\IdentityRepositoryInterface;
use Webman\Http\Request;

class SessionMethod implements AuthenticationMethodInterface
{
    protected IdentityRepositoryInterface $identityRepository;
    protected string $name = Guard::SESSION_AUTH_ID;

    public function __construct(IdentityRepositoryInterface $identity, array $config = [])
    {
        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }
        $this->identityRepository = $identity;
    }

    /**
     * @inheritDoc
     */
    public function authenticate(Request $request): ?IdentityInterface
    {
        if ($id = $request->session->get($this->name)) {
            return $this->identityRepository->findIdentity($id);
        }

        return null;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
