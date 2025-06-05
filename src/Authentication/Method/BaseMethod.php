<?php

namespace WebmanTech\Auth\Authentication\Method;

use WebmanTech\Auth\Interfaces\AuthenticationMethodInterface;
use WebmanTech\Auth\Interfaces\IdentityInterface;
use WebmanTech\Auth\Interfaces\IdentityRepositoryInterface;
use Webman\Http\Request;

abstract class BaseMethod implements AuthenticationMethodInterface
{
    protected ?string $tokenType = null;

    public function __construct(protected IdentityRepositoryInterface $identityRepository, array $config = [])
    {
        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }
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
