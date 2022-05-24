<?php

namespace Kriss\WebmanAuth\Authentication\Method;

use Kriss\WebmanAuth\Interfaces\AuthenticationMethodInterface;
use Kriss\WebmanAuth\Interfaces\IdentityInterface;
use Kriss\WebmanAuth\Interfaces\IdentityRepositoryWithTokenInterface;
use Webman\Http\Request;

class HttpBearerMethod implements AuthenticationMethodInterface
{
    protected IdentityRepositoryWithTokenInterface $identityRepository;
    protected string $name = 'Authorization';
    protected ?string $tokenType = null;

    public function __construct(IdentityRepositoryWithTokenInterface $identity, array $config = [])
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
        if ($token = $this->getCredentials($request)) {
            return $this->identityRepository->findIdentityByToken($token, $this->tokenType);
        }

        return null;
    }

    /**
     * @param Request $request
     * @return string|null
     */
    protected function getCredentials(Request $request): ?string
    {
        $authorization = $request->header($this->name);
        if (preg_match('/Bearer\s+(.*)$/i', $authorization, $matches)) {
            return $matches[1];
        }
        return null;
    }
}