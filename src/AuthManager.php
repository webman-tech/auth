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
     * 默认 guard 名（实例内缓存）：同一实例内多次 guard(null) 时避免重复查 config
     */
    protected ?string $defaultGuardName = null;

    /**
     * @param string|null $name
     * @return GuardInterface
     */
    public function guard(?string $name = null): GuardInterface
    {
        if ($name === null) {
            $name = $this->defaultGuardName ??= (string)ConfigHelper::get('auth.default');
        }
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
        $config = (array)ConfigHelper::get("auth.guards.{$name}");
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
