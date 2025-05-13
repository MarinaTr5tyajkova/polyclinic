<?php

namespace Src;

use Error;

use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use FastRoute\DataGenerator\MarkBased;
use FastRoute\Dispatcher\MarkBased as Dispatcher;
use Src\Traits\SingletonTrait;

class Route
{
    //Используем методы трейта
    use SingletonTrait;

    //Свойство для хранения текущего маршрута
    private string $currentRoute = '';
    private $currentHttpMethod;

    //Свойство для префикса для всех маршрутов
    private string $prefix = '';

    //Классы для использования внешнего маршрутизатора
    private RouteCollector $routeCollector;

    //Добавляет маршрут, устанавливает его текущим и возвращает объект
    public static function add($httpMethod, string $route, array $action): self
    {
        self::single()->routeCollector->addRoute($httpMethod, $route, $action);
        self::single()->currentHttpMethod = $httpMethod;
        self::single()->currentRoute = $route;
        return self::single();
    }

    //Добавляет префикс для обозначенных маршрутов
    public static function group(string $prefix, callable $callback): void
    {
        self::single()->routeCollector->addGroup($prefix, $callback);
        Middleware::single()->group($prefix, $callback);
    }

    //Конструктор скрыт. Вызывается только один раз
    private function __construct()
    {
        $this->routeCollector = new RouteCollector(new Std(), new MarkBased());
    }

    public function setPrefix(string $value = ''): self
    {
        $this->prefix = $value;
        return $this;
    }

    public function redirect(string $url): void
    {
        header('Location: ' . $this->getUrl($url));
    }

    public function getUrl(string $url): string
    {
        return $this->prefix . $url;
    }

    //Добавление middlewares для текущего маршрута
    public function middleware(...$middlewares): self
    {
        Middleware::single()->add($this->currentHttpMethod, $this->currentRoute, $middlewares);
        return $this;
    }

    public function start(): void {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        error_log("Request method: $httpMethod");
        error_log("Request URI: $uri");

        // Удаляем строку запроса (?foo=bar) и декодируем URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        $uri = substr($uri, strlen($this->prefix));

        error_log("Processed URI: $uri");

        // Создаем диспетчер маршрутов
        $dispatcher = new Dispatcher($this->routeCollector->getData());

        // Определяем маршрут
        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                error_log("Route not found for URI: $uri");
                throw new Error('NOT_FOUND');
            case Dispatcher::METHOD_NOT_ALLOWED:
                error_log("Method not allowed for URI: $uri");
                throw new Error('METHOD_NOT_ALLOWED');
            case Dispatcher::FOUND:
                // Получаем обработчик маршрута и параметры
                $handler = $routeInfo[1];
                $vars = array_values($routeInfo[2]);

                error_log("Route found: handler=" . print_r($handler, true));

                // Создаем объект Request
                $request = new \Src\Request();

                // Получаем имя класса контроллера и метод
                $controllerClass = $handler[0];
                $method = $handler[1];

                // Создаем экземпляр контроллера
                $controller = new $controllerClass();

                // Вызываем метод контроллера с передачей Request
                call_user_func([$controller, $method], $request);

                break;
        }
    }
}