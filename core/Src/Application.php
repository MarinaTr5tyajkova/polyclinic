<?php

namespace Src;

use Error;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;
use Src\Auth\Auth;

class Application
{
    private Settings $settings;
    private Route $route;
    private Capsule $dbManager;
    private Auth $auth;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
        $this->route = Route::single()->setPrefix($this->settings->getRootPath());
        $this->dbManager = new Capsule();

        // Инициализация базы данных
        $this->dbRun();

        // Инициализация Auth
        $identityClass = $this->settings->app['identity'];

        if (!class_exists($identityClass)) {
            throw new \RuntimeException("Identity class {$identityClass} not found. Check namespace and autoload configuration.");
        }

        $this->auth = new $this->settings->app['admin'](new $identityClass());
        Auth::init(new $identityClass());
    }


    public function __get($key)
    {
        switch ($key) {
            case 'settings':
                return $this->settings;
            case 'route':
                return $this->route;
            case 'auth':
                return $this->auth;
            default:
                throw new Error('Accessing a non-existent property: ' . $key);
        }
    }

    private function dbRun()
    {
        $this->dbManager->addConnection($this->settings->getDbSetting());
        $this->dbManager->setEventDispatcher(new Dispatcher(new Container));
        $this->dbManager->setAsGlobal();
        $this->dbManager->bootEloquent();
    }

    public function run(): void
    {
        $this->route->start();
    }
}