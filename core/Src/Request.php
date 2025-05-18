<?php

namespace Src;

class Request
{
    public string $method;
    public array $data;
    public ?array $body = null;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->data = array_merge($_GET, $_POST);

        $json = file_get_contents('php://input');
        if ($json !== false) {
            $this->body = json_decode($json, true) ?? [];
        }
    }

    public function has(string $key): bool
    {
        return isset($this->data[$key]) || isset($this->body[$key]);
    }

    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $this->body[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->data, $this->body ?? []);
    }

    /**
     * Проверяет, был ли загружен файл с указанным ключом.
     *
     * @param string $key
     * @return bool
     */
    public function hasFile(string $key): bool
    {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] !== UPLOAD_ERR_NO_FILE;
    }

    /**
     * Возвращает информацию о загруженном файле.
     *
     * @param string $key
     * @return array|null
     */
    public function file(string $key): ?array
    {
        return $_FILES[$key] ?? null;
    }
}
