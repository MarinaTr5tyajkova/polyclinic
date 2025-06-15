<?php

namespace App\Providers;

use Src\Provider\AbstractProvider;

class AuthProvider extends AbstractProvider
{

    public function register(): void
    {
        // Регистрация сервисов аутентификации, если нужно
    }

    public function boot(): void
    {
        $authClass = $this->app->settings->getAuthClassName();
        $identityClass = $this->app->settings->getIdentityClassName();

        $authClass::init(new $identityClass);
        $this->app->bind('auth', new $authClass);
    }
}
