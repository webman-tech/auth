<?php

use WebmanTech\Auth\Authentication\FailureHandler\RedirectHandler;
use WebmanTech\Auth\Authentication\Method\SessionMethod;
use WebmanTech\Auth\Interfaces\IdentityRepositoryInterface;

return [
    // 默认 guard
    'default' => 'user',
    // 多 guard 配置
    'guards' => [
        'user' => [
            'class' => WebmanTech\Auth\Guard\Guard::class,
            'identityRepository' => function () {
                //return new User();
            },
            'authenticationMethod' => function ($identityRepository) {
                //return new SessionMethod($identityRepository);
            },
            'authenticationFailureHandler' => function () {
                //return new RedirectHandler(route('admin.login'));
            },
        ],
        // session 的例子
        'example_use_session' => [
            'class' => WebmanTech\Auth\Guard\Guard::class,
            'identityRepository' => function () {
                return new app\model\User();
            },
            'authenticationMethod' => function (IdentityRepositoryInterface $identityRepository) {
                // 通过 session 认证
                return new SessionMethod($identityRepository);
            },
            'authenticationFailureHandler' => function () {
                // 认证失败跳转到 登录页面
                return new RedirectHandler(route('admin.login'));
            },
        ],
        // api 接口的例子
        'example_use_api_token' => [
            'class' => WebmanTech\Auth\Guard\Guard::class,
            'identityRepository' => function () {
                return new app\model\User();
            },
            'authenticationMethod' => function (IdentityRepositoryInterface $identityRepository) {
                // 通过 request 请求参数授权，默认 name 为 access-token，放在 get 或 post 里都可以
                return new WebmanTech\Auth\Authentication\Method\RequestMethod($identityRepository);
                // 通过 request header 授权，默认 name 为 X-Api-Key
                //return new WebmanTech\Auth\Authentication\Method\HttpHeaderMethod($identityRepository);
            },
            'authenticationFailureHandler' => function () {
                // 响应 401 http 状态码
                //return new WebmanTech\Auth\Authentication\FailureHandler\ResponseHandler();
                // 抛出 401 异常，交给框架 ErrorHandler 处理，可以控制输出内容
                return new WebmanTech\Auth\Authentication\FailureHandler\ThrowExceptionHandler();
            },
        ]
    ]
];
