<?php

use Kriss\WebmanAuth\Authentication\FailureHandler\RedirectHandler;
use Kriss\WebmanAuth\Authentication\Method\SessionMethod;
use Kriss\WebmanAuth\Interfaces\IdentityRepositoryInterface;

return [
    'default' => 'user',
    'guards' => [
        'user' => [
            'identifyRepository' => function () {
                return new User();
            },
            'authenticationMethod' => function (IdentityRepositoryInterface $identityRepository) {
                return new SessionMethod($identityRepository);
            },
            'authenticationFailureHandler' => function () {
                return new RedirectHandler(route('/auth/login'));
            },
        ],
    ]
];