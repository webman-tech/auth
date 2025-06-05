<?php

namespace WebmanTech\Auth\Authentication\Method;

use Webman\Http\Request;
use WebmanTech\Auth\Guard\Guard;

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
        /** @phpstan-ignore-next-line */
        return $request->session()->get($this->name);
    }
}
