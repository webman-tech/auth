<?php

use Kriss\WebmanAuth\Authentication\FailureHandler\RedirectHandler;
use Kriss\WebmanAuth\Authentication\Method\SessionMethod;
use Kriss\WebmanAuth\Interfaces\IdentityRepositoryInterface;
use Kriss\WebmanAuth\Interfaces\IdentityRepositoryWithTokenInterface;

return [
    'default' => 'user',
    'guards' => [
        'user' => [
            'class' => Kriss\WebmanAuth\Guard\Guard::class,
            'identityRepository' => function () {
                return new app\model\User();
            },
            'authenticationMethod' => function (IdentityRepositoryWithTokenInterface $identityRepository) {
                return new Kriss\WebmanAuth\Authentication\Method\RequestMethod($identityRepository);
            },
            'authenticationFailureHandler' => function () {
                return new Kriss\WebmanAuth\Authentication\FailureHandler\ThrowExceptionHandler();
            },
        ],
        'admin' => [
            'class' => Kriss\WebmanAuth\Guard\Guard::class,
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
