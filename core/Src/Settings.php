<?php

namespace Src;

use Error;

class Settings
{
    private array $_settings;

    public function __construct(array $settings = [])
    {
        $this->_settings = $settings;
    }

    /**
     * Магический геттер для доступа к настройкам
     * @param string $key
     * @return mixed
     * @throws Error
     */
    public function __get(string $key)
    {
        if (array_key_exists($key, $this->_settings)) {
            return $this->_settings[$key];
        }
        throw new Error("Accessing a non-existent property: {$key}");
    }

    /**
     * Получить корневой путь приложения (например, '/polyclinic')
     * @return string
     */
    public function getRootPath(): string
    {
        return !empty($this->_settings['path']['root'])
            ? '/' . trim($this->_settings['path']['root'], '/')
            : '';
    }

    /**
     * Получить путь к папке с видами (views)
     * @return string
     */
    public function getViewsPath(): string
    {
        return !empty($this->_settings['path']['views'])
            ? '/' . trim($this->_settings['path']['views'], '/')
            : '';
    }

    /**
     * Получить настройки базы данных
     * @return array
     */
    public function getDbSetting(): array
    {
        return $this->_settings['db'] ?? [];
    }

    /**
     * Получить путь к папке с маршрутами
     * @return string
     */
    public function getRoutePath(): string
    {
        return !empty($this->_settings['path']['routes'])
            ? '/' . trim($this->_settings['path']['routes'], '/')
            : '/routes';
    }

    /**
     * Получить имя класса для аутентификации
     * @return string
     */
    public function getAuthClassName(): string
    {
        return $this->_settings['app']['admin'] ?? 'Src\Auth\Auth';
    }

    /**
     * Получить имя класса для идентификации пользователя
     * @return string
     */
    public function getIdentityClassName(): string
    {
        return $this->_settings['app']['identity'] ?? 'Model\User';
    }

    /**
     * Удаляет middleware из массива routeAppMiddleware по имени
     * @param string $name
     * @return void
     */
    public function removeAppMiddleware(string $name): void
    {
        if (isset($this->_settings['app']['routeAppMiddleware'][$name])) {
            unset($this->_settings['app']['routeAppMiddleware'][$name]);
        }
    }
}
