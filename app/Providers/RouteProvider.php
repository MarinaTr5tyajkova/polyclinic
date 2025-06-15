<?php

namespace App\Providers;

use Src\Provider\AbstractProvider;
use Src\Route;

class RouteProvider extends AbstractProvider
{
    public function register(): void
    {
        // Регистрация сервисов, если нужно
    }

    public function boot(): void
    {
        // Привязываем объект маршрутизации с префиксом корня (например, /polyclinic)
        $this->app->bind('route', Route::single()->setPrefix($this->app->settings->getRootPath()));

        if ($this->checkPrefix('/api')) {
            // Удаляем ненужные middleware для API
            $this->app->settings->removeAppMiddleware('json');
            $this->app->settings->removeAppMiddleware('csrf');
            $this->app->settings->removeAppMiddleware('specialChars');

            // Загружаем веб-маршруты (без префикса /api)
            require_once __DIR__ . '/../../' . $this->app->settings->getRoutePath() . '/web.php';

            // Загружаем API маршруты в группу с префиксом /api
            Route::group('/api', function () {
                require_once __DIR__ . '/../../' . $this->app->settings->getRoutePath() . '/api.php';
            });

            return;
        }

        // Если не API, загружаем только веб-маршруты
        require_once __DIR__ . '/../../' . $this->app->settings->getRoutePath() . '/web.php';
    }

    private function getUri(): string
    {
        return substr($_SERVER['REQUEST_URI'], strlen($this->app->settings->getRootPath()));
    }

    private function checkPrefix(string $prefix): bool
    {
        $uri = $this->getUri();
        return strpos($uri, $prefix) === 0;
    }
}
