<?php

namespace app\middleware;

use Kriss\WebmanAuth\Middleware\SetAuthGuard;

class SetAuthGuardAdmin extends SetAuthGuard
{
    public function __construct()
    {
        parent::__construct('admin');
    }
}
