<?php

namespace Kriss\WebmanAuth\Authentication\Method;

use Kriss\WebmanAuth\Interfaces\AuthenticationMethodInterface;
use Kriss\WebmanAuth\Interfaces\IdentityInterface;
use Kriss\WebmanAuth\Interfaces\IdentityRepositoryWithTokenInterface;
use Webman\Http\Request;

/**
 * Http Basic 验证方式
 * 注意：不支持 username 和 password 中有 : 的情况
 */
class HttpBasicMethod implements AuthenticationMethodInterface
{
    protected IdentityRepositoryWithTokenInterface $identityRepository;
    protected string $name = 'Authorization';
    protected ?string $tokenType = null;

    public function __construct(IdentityRepositoryWithTokenInterface $identity, array $config = [])
    {
        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }
        $this->identityRepository = $identity;
    }

    /**
     * @inheritDoc
     */
    public function authenticate(Request $request): ?IdentityInterface
    {
        [$username, $password] = $this->getCredentials($request);

        if ($username && $password) {
            return $this->identityRepository->findIdentityByToken($username . ':' . $password, $this->tokenType);
        }

        return null;
    }

    /**
     * @param Request $request
     * @return string[] [$username, $password]
     */
    protected function getCredentials(Request $request): array
    {
        $authorization = $request->header($this->name);
        if (preg_match('/Basic\s+(.*)$/i', $authorization, $matches)) {
            $credentials = base64_decode($matches[1]);
            if (strpos($credentials, ':') !== false) {
                return explode(':', $credentials);
            }
        }

        // 需要支持 http://admin:123456@127.0.0.1:8787/admin/auth/login 的形式
        // 目前 webman 貌似不支持

        return [null, null];
    }
}