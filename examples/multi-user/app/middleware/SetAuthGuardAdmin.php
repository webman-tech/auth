<?php

namespace app\middleware;

use WebmanTech\Auth\Middleware\SetAuthGuard;

class SetAuthGuardAdmin extends SetAuthGuard
{
    public function __construct()
    {
        parent::__construct('admin');
    }
}
