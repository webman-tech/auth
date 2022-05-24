<?php

namespace Kriss\WebmanAuth\Authentication\Method;

use InvalidArgumentException;
use Kriss\WebmanAuth\Interfaces\IdentityRepositoryInterface;
use Tinywan\Jwt\Exception\JwtTokenException;
use Tinywan\Jwt\JwtToken;
use Webman\Http\Request;

/**
 * tinywan/jwt 认证方式
 * @link https://github.com/Tinywan/webman-jwt
 */
class TinywanJwtMethod extends BaseMethod
{
    protected bool $throwException = false;

    public function __construct(IdentityRepositoryInterface $identity, array $config = [])
    {
        parent::__construct($identity, $config);

        if (!class_exists('Tinywan\Jwt\JwtToken')) {
            throw new InvalidArgumentException('请先安装 tinywan/jwt: composer require tinywan/jwt');
        }
    }

    /**
     * @inheritDoc
     */
    protected function getCredentials(Request $request): ?string
    {
        try {
            return JwtToken::getCurrentId();
        } catch (JwtTokenException $e) {
            if ($this->throwException) {
                throw $e;
            }
            return null;
        }
    }
}
