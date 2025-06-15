<?php
return [
    //Класс аутентификации
    'admin' => \Src\Auth\Auth::class,
    //Клас пользователя
    'identity' => \Model\User::class,
    //Классы для middleware
    'routeMiddleware' => [
        'admin' => \Middlewares\AuthMiddleware::class,
        'employee' => \Middlewares\EmployeeMiddleware::class,
    ],
    'validators' => [
        'login' => \Validators\LoginValidator::class,
        'password' => \Validators\PasswordValidator::class,
        'required' => \Validators\RequireValidator::class,
        'unique' => \Validators\UniqueValidator::class,
        'age' => \Validators\AgeValidator::class

    ],
    'routeAppMiddleware' => [
        'trim' => \Middlewares\TrimMiddleware::class,
        'specialChars' => \Middlewares\SpecialCharsMiddleware::class,
        'csrf' => \Middlewares\CSRFMiddleware::class,
        'bearer' => Middlewares\BearerTokenMiddleware::class,
    ],

    'providers' => [
        'kernel' => \App\Providers\KernelProvider::class,
        'route' => \App\Providers\RouteProvider::class,
        'db' => \App\Providers\DBProvider::class,
        'auth' => \App\Providers\AuthProvider::class,
    ],


];
