<?php

namespace app\middleware;

use Kriss\WebmanAuth\Interfaces\GuardInterface;
use Kriss\WebmanAuth\facade\Auth;
use Kriss\WebmanAuth\Middleware\Authentication;

class AuthenticateAdmin extends Authentication
{
    /**
     * @inheritDoc
     */
    public function getGuard(): GuardInterface
    {
        return Auth::guard('admin');
    }
}
