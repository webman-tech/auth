<?php

namespace WebmanTech\Auth\Authentication\Method;

use Webman\Http\Request;

/**
 * Http Basic 验证方式
 * 注意：不支持 username 和 password 中有 : 的情况
 */
class HttpBasicMethod extends HttpAuthorizationMethod
{
    protected string $pattern = '/Basic\s+(.*)$/i';

    /**
     * @inheritDoc
     */
    protected function getCredentials(Request $request): ?string
    {
        if ($credentials = parent::getCredentials($request)) {
            $credentials = base64_decode($credentials);
            if (strpos($credentials, ':') !== false) {
                return $credentials;
            }
        }

        // 需要支持 http://admin:123456@127.0.0.1:8787/admin/auth/login 的形式
        // 目前 webman 貌似不支持

        return null;
    }
}