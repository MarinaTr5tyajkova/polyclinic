<?php

namespace Src;

use Error;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;
use Src\Auth\Auth;

class Application
{
    // Список провайдеров приложения
    private array $providers = [];
    // Хранилище сервисов и данных приложения
    private array $binds = [];

    private Settings $settings;
    private Route $route;
    private Capsule $dbManager;
    private Auth $auth;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;

        // Добавляем провайдеры из конфигурации
        $this->addProviders($this->settings->app['providers'] ?? []);

        // Регистрируем провайдеры (вызываем register())
        $this->registerProviders();

        // Инициализация базы данных (если не реализовано в провайдерах)
        $this->dbManager = new Capsule();
        $this->dbRun();

        // Инициализация Auth
        $identityClass = $this->settings->app['identity'] ?? null;
        if (!$identityClass || !class_exists($identityClass)) {
            throw new \RuntimeException("Identity class {$identityClass} not found. Check namespace and autoload configuration.");
        }

        $this->auth = new $this->settings->app['admin'](new $identityClass());
        Auth::init(new $identityClass());

        // Привязываем основные сервисы в контейнер
        $this->bind('settings', $this->settings);
        $this->bind('auth', $this->auth);

        // Инициализируем маршрутизацию с префиксом
        $this->route = Route::single()->setPrefix($this->settings->getRootPath());
        $this->bind('route', $this->route);

        // Запускаем boot() у провайдеров (после инициализации основных сервисов)
        $this->bootProviders();
    }


    public function addProviders(array $providers): void
    {
        foreach ($providers as $key => $class) {
            if (class_exists($class)) {
                $this->providers[$key] = new $class($this);
            } else {
                throw new \RuntimeException("Provider class {$class} not found.");
            }
        }
    }


    private function registerProviders(): void
    {
        foreach ($this->providers as $provider) {
            $provider->register();
        }
    }


    private function bootProviders(): void
    {
        foreach ($this->providers as $provider) {
            $provider->boot();
        }
    }


    private function dbRun(): void
    {
        $this->dbManager->addConnection($this->settings->getDbSetting());
        $this->dbManager->setEventDispatcher(new Dispatcher(new Container));
        $this->dbManager->setAsGlobal();
        $this->dbManager->bootEloquent();

        // Привязываем менеджер базы в контейнер для доступа
        $this->bind('dbManager', $this->dbManager);
    }


    public function bind(string $key, $value): void
    {
        $this->binds[$key] = $value;
    }


    public function __get($key)
    {
        if (array_key_exists($key, $this->binds)) {
            return $this->binds[$key];
        }

        throw new Error("Accessing a non-existent property in application: {$key}");
    }


    public function run(): void
    {
        $this->route->start();
    }
}
