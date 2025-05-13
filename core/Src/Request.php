<?php

namespace Src;

class Request
{
    public string $method;
    public array $data;
    public ?array $body = null; // Инициализируем как nullable

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->data = array_merge($_GET, $_POST);

        // Инициализация body для JSON-запросов
        $json = file_get_contents('php://input');
        if ($json !== false) {
            $this->body = json_decode($json, true) ?? [];
        }
    }

    /**
     * Проверяет наличие параметра в запросе.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]) || isset($this->body[$key]);
    }

    /**
     * Получает значение параметра из запроса.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $this->body[$key] ?? $default;
    }

    /**
     * Возвращает все данные запроса (GET + POST + JSON body).
     *
     * @return array
     */
    public function all(): array
    {
        return array_merge($this->data, $this->body ?? []);
    }
}