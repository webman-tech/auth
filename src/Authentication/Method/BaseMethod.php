<?php

namespace Kriss\WebmanAuth\Authentication\Method;

use Kriss\WebmanAuth\Interfaces\AuthenticationMethodInterface;
use Kriss\WebmanAuth\Interfaces\IdentityInterface;
use Kriss\WebmanAuth\Interfaces\IdentityRepositoryInterface;
use Webman\Http\Request;

abstract class BaseMethod implements AuthenticationMethodInterface
{
    protected IdentityRepositoryInterface $identityRepository;
    protected ?string $tokenType = null;

    public function __construct(IdentityRepositoryInterface $identityRepository, array $config = [])
    {
        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }
        $this->identityRepository = $identityRepository;
    }

    /**
     * @inheritDoc
     */
    public function authenticate(Request $request): ?IdentityInterface
    {
        if ($token = $this->getCredentials($request)) {
            return $this->identityRepository->findIdentity($token, $this->tokenType);
        }

        return null;
    }

    /**
     * 获取凭证数据
     * @param Request $request
     * @return string|null
     */
    abstract protected function getCredentials(Request $request): ?string;
}