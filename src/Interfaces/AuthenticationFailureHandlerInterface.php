<?php

namespace Kriss\WebmanAuth\Interfaces;

use Webman\Http\Request;
use Webman\Http\Response;

interface AuthenticationFailureHandlerInterface
{
    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response;
}
