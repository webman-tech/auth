<?php

use WebmanTech\Auth\Authentication\FailureHandler\RedirectHandler;
use WebmanTech\Auth\Authentication\Method\SessionMethod;
use WebmanTech\Auth\Interfaces\IdentityRepositoryInterface;

return [
    'default' => 'user',
    'guards' => [
        'user' => [
            'class' => WebmanTech\Auth\Guard\Guard::class,
            'identityRepository' => function () {
                return new app\model\User();
            },
            'authenticationMethod' => function (IdentityRepositoryInterface $identityRepository) {
                return new WebmanTech\Auth\Authentication\Method\RequestMethod($identityRepository);
            },
            'authenticationFailureHandler' => function () {
                return new WebmanTech\Auth\Authentication\FailureHandler\ThrowExceptionHandler();
            },
        ],
        'admin' => [
            'class' => WebmanTech\Auth\Guard\Guard::class,
            'identityRepository' => function () {
                return new app\model\Admin();
            },
            'authenticationMethod' => function (IdentityRepositoryInterface $identityRepository) {
                return new SessionMethod($identityRepository);
            },
            'authenticationFailureHandler' => function () {
                return new RedirectHandler(route('admin.login'));
            },
        ],
    ]
];
