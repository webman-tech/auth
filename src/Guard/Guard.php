<?php

namespace WebmanTech\Auth\Guard;

use WebmanTech\Auth\Authentication\Method\CompositeMethod;
use WebmanTech\Auth\Authentication\Method\SessionMethod;
use WebmanTech\Auth\Interfaces\AuthenticationFailureHandlerInterface;
use WebmanTech\Auth\Interfaces\AuthenticationMethodInterface;
use WebmanTech\Auth\Interfaces\GuardInterface;
use WebmanTech\Auth\Interfaces\IdentityInterface;
use WebmanTech\Auth\Interfaces\IdentityRepositoryInterface;
use WebmanTech\CommonUtils\Session;

class Guard implements GuardInterface
{
    public const SESSION_AUTH_ID = '__auth_id';

    protected array $config = [
        'identityRepository' => null,
        'authenticationMethod' => null,
        'authenticationFailureHandler' => null,
        'sessionEnable' => false,
    ];

    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->config, $config);
    }

    protected ?IdentityRepositoryInterface $identityRepository = null;

    /**
     * @return IdentityRepositoryInterface
     */
    protected function getIdentityRepository(): IdentityRepositoryInterface
    {
        if ($this->identityRepository === null) {
            $value = call_user_func($this->config['identityRepository']);
            if (!$value instanceof IdentityRepositoryInterface) {
                throw new \InvalidArgumentException('identityRepository must be IdentityRepositoryInterface');
            }
            $this->identityRepository = $value;
        }
        return $this->identityRepository;
    }

    /**
     * @var AuthenticationMethodInterface|null
     */
    protected ?AuthenticationMethodInterface $authenticationMethod = null;

    /**
     * @inheritDoc
     */
    public function getAuthenticationMethod(): AuthenticationMethodInterface
    {
        if ($this->authenticationMethod === null) {
            $value = call_user_func($this->config['authenticationMethod'], $this->getIdentityRepository());
            if (!$value instanceof AuthenticationMethodInterface) {
                throw new \InvalidArgumentException('authenticationMethod must be AuthenticationMethodInterface');
            }
            $this->authenticationMethod = $value;
        }
        return $this->authenticationMethod;
    }

    /**
     * @var AuthenticationFailureHandlerInterface|null
     */
    protected ?AuthenticationFailureHandlerInterface $authenticationFailureHandler = null;

    /**
     * @inheritDoc
     */
    public function getAuthenticationFailedHandler(): AuthenticationFailureHandlerInterface
    {
        if ($this->authenticationFailureHandler === null) {
            $value = call_user_func($this->config['authenticationFailureHandler'], $this->getAuthenticationMethod());
            if (!$value instanceof AuthenticationFailureHandlerInterface) {
                throw new \InvalidArgumentException('authenticationFailureHandler must be AuthenticationFailureHandlerInterface');
            }
            $this->authenticationFailureHandler = $value;
        }
        return $this->authenticationFailureHandler;
    }

    protected ?IdentityInterface $identity = null;

    /**
     * @inheritDoc
     */
    public function login(IdentityInterface $identity): void
    {
        $this->identity = $identity;

        if ($this->isSessionEnable()) {
            Session::getCurrent()->set(static::SESSION_AUTH_ID, $this->getId());
        }
    }

    /**
     * @inheritDoc
     */
    public function logout(): void
    {
        if ($this->isGuest()) {
            return;
        }

        $this->identity = null;

        if ($this->isSessionEnable()) {
            Session::getCurrent()->delete(static::SESSION_AUTH_ID);
        }
    }

    protected ?bool $isSessionEnable = null;

    /**
     * 是否允许 session
     * @return bool
     */
    protected function isSessionEnable(): bool
    {
        if ($this->isSessionEnable !== null) {
            return $this->isSessionEnable;
        }

        $sessionEnable = $this->config['sessionEnable'];
        if ($sessionEnable) {
            return $this->isSessionEnable = true;
        }
        $authenticationMethod = $this->getAuthenticationMethod();
        if ($authenticationMethod instanceof SessionMethod) {
            return $this->isSessionEnable = true;
        }
        if ($authenticationMethod instanceof CompositeMethod) {
            foreach ($authenticationMethod->getMethods() as $method) {
                if ($method instanceof SessionMethod) {
                    return $this->isSessionEnable = true;
                }
            }
        }
        return $this->isSessionEnable = false;
    }

    /**
     * @inheritDoc
     */
    public function isGuest(): bool
    {
        return $this->identity === null;
    }

    /**
     * @inheritDoc
     */
    public function getUser(bool $refresh = false): ?IdentityInterface
    {
        if (!$this->identity instanceof IdentityInterface) {
            return null;
        }
        if ($refresh) {
            $this->identity = $this->identity->refreshIdentity();
        }
        return $this->identity;
    }

    /**
     * @inheritDoc
     */
    public function getId(): ?string
    {
        return $this->identity instanceof IdentityInterface ? $this->identity->getId() : null;
    }
}
