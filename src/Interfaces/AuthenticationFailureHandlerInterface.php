<?php

namespace WebmanTech\Auth\Interfaces;

use WebmanTech\CommonUtils\Request;
use WebmanTech\CommonUtils\Response;

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
