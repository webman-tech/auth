<?php

namespace Kriss\WebmanAuth\Interfaces;

use Webman\Http\Request;
use Webman\Http\Response;

/**
 * 授权失败处理器接口
 */
interface AuthenticationFailureHandlerInterface
{
    /**
     * 处理授权失败响应
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response;
}
