<?php

namespace WebmanTech\Auth\Authentication\Method;

use Webman\Http\Request;
use WebmanTech\Auth\Interfaces\AuthenticationMethodInterface;
use WebmanTech\Auth\Interfaces\IdentityInterface;

class CompositeMethod implements AuthenticationMethodInterface
{
    public function __construct(protected array $methods)
    {
        foreach ($this->methods as $method) {
            if (!$method instanceof AuthenticationMethodInterface) {
                throw new \InvalidArgumentException('$method must be ' . AuthenticationMethodInterface::class);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function authenticate(Request $request): ?IdentityInterface
    {
        foreach ($this->methods as $method) {
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
