<?php
return [
    //Класс аутентификации
    'admin' => \Src\Auth\Auth::class,
    //Клас пользователя
    'identity' => \Model\User::class,
    //Классы для middleware
    'routeMiddleware' => [
        'admin' => \Middlewares\AuthMiddleware::class,
        'admin' => \Middlewares\AdminMiddleware::class,
        'employee' => \Middlewares\EmployeeMiddleware::class,
    ]
];
