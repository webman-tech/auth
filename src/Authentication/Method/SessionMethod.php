<?php

namespace WebmanTech\Auth\Authentication\Method;

use WebmanTech\Auth\Guard\Guard;
use WebmanTech\CommonUtils\Request;

/**
 * Session方式
 */
class SessionMethod extends BaseMethod
{
    protected string $name = Guard::SESSION_AUTH_ID;

    /**
     * @inheritDoc
     */
    protected function getCredentials(Request $request): ?string
    {
        return $request->getSession()?->get($this->name);
    }
}
