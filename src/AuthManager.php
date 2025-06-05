<?php

namespace WebmanTech\Auth;

use InvalidArgumentException;
use WebmanTech\Auth\Guard\Guard;
use WebmanTech\Auth\Helper\ConfigHelper;
use WebmanTech\Auth\Interfaces\GuardInterface;

class AuthManager
{
    protected array $guards = [];

    /**
     * @param string|null $name
     * @return GuardInterface
     */
    public function guard(?string $name = null): GuardInterface
    {
        $name = $name ?? ConfigHelper::get(('auth.default'));
        if (!isset($this->guards[$name])) {
            $this->guards[$name] = $this->createGuard($this->getConfig($name));
        }

        return $this->guards[$name];
    }

    /**
     * @param string $name
     * @return array
     */
    protected function getConfig(string $name): array
    {
        $config = ConfigHelper::get("auth.guards.{$name}");
        if (!$config) {
            throw new InvalidArgumentException($name . 'not exist in auth.guards');
        }
        return $config;
    }

    /**
     * @param array $config
     * @return GuardInterface
     */
    protected function createGuard(array $config): GuardInterface
    {
        $guardClass = $config['class'] ?? Guard::class;
        $guard = new $guardClass($config);
        if (!$guard instanceof GuardInterface) {
            throw new InvalidArgumentException('class 必须是 GuardInterface 的实现');
        }
        return $guard;
    }
}
