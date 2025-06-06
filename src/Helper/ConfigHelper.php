<?php

namespace WebmanTech\Auth\Helper;

/**
 * @internal
 */
final class ConfigHelper
{
    /**
     * 获取配置
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, mixed $default = null)
    {
        return config("plugin.webman-tech.auth.{$key}", $default);
    }
}
