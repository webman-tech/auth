<?php

namespace Kriss\WebmanAuth\facade;

use Kriss\WebmanAuth\AuthManager;
use Kriss\WebmanAuth\Interfaces\GuardInterface;

/**
 * @method static GuardInterface guard(string $name = null)
 */
class Auth
{
    const REQUEST_INSTANCE_NAME = 'auth_manager__';

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        $request = request();
        if (!$request->{static::REQUEST_INSTANCE_NAME}) {
            $request->{static::REQUEST_INSTANCE_NAME} = new AuthManager();
        }

        return $request->{static::REQUEST_INSTANCE_NAME}->{$name}(... $arguments);
    }
}