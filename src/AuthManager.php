<?php

namespace Kriss\WebmanAuth;

use InvalidArgumentException;
use Kriss\WebmanAuth\Guard\Guard;
use Kriss\WebmanAuth\Interfaces\GuardInterface;

class AuthManager
{
    protected array $guards = [];

    /**
     * @param string|null $name
     * @return GuardInterface
     */
    public function guard(string $name = null): GuardInterface
    {
        $name = $name ?? config('plugin.kriss.webman-auth.auth.default');
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
        $key = "plugin.kriss.webman-auth.auth.guards.{$name}";
        $config = config($key);
        if (!$config) {
            throw new InvalidArgumentException($key . ' 未配置');
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
