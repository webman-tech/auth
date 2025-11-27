<?php

namespace WebmanTech\Auth\Authentication\Method;

use InvalidArgumentException;
use Tinywan\Jwt\Exception\JwtTokenException;
use Tinywan\Jwt\JwtToken;
use WebmanTech\Auth\Interfaces\IdentityRepositoryInterface;
use WebmanTech\CommonUtils\Request;

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

        if (!class_exists(\Tinywan\Jwt\JwtToken::class)) {
            throw new InvalidArgumentException('请先安装 tinywan/jwt: composer require tinywan/jwt');
        }
    }

    /**
     * @inheritDoc
     */
    protected function getCredentials(Request $request): ?string
    {
        try {
            /** @phpstan-ignore-next-line */
            return JwtToken::getCurrentId();
        } catch (JwtTokenException $e) {
            if ($this->throwException) {
                throw $e;
            }
            return null;
        }
    }
}
