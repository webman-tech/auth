<?php

namespace Kriss\WebmanAuth\Authentication\Method;

use Kriss\WebmanAuth\Interfaces\AuthenticationMethodInterface;
use Kriss\WebmanAuth\Interfaces\IdentityInterface;
use Webman\Http\Request;

class CompositeMethod implements AuthenticationMethodInterface
{
    /**
     * @var AuthenticationMethodInterface[]
     */
    protected array $methods;

    public function __construct(array $methods)
    {
        $this->methods = $methods;
    }

    /**
     * @inheritDoc
     */
    public function authenticate(Request $request): ?IdentityInterface
    {
        foreach ($this->methods as $method) {
            if (!$method instanceof AuthenticationMethodInterface) {
                throw new \InvalidArgumentException('$method must be ' . AuthenticationMethodInterface::class);
            }

            $identity = $method->authenticate($request);
            if ($identity !== null) {
                return $identity;
            }
        }
        return null;
    }

    /**
     * @return AuthenticationMethodInterface[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }
}