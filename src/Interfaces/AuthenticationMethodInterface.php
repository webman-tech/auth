<?php

namespace Kriss\WebmanAuth\Interfaces;

use Webman\Http\Request;

interface AuthenticationMethodInterface
{
    /**
     * @param Request $request
     * @return IdentityInterface|null
     */
    public function authenticate(Request $request): ?IdentityInterface;
}