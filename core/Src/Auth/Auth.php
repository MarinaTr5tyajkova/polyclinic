<?php

namespace Src\Auth;

use Src\Session;
use RuntimeException;

class Auth
{
    // Свойство для хранения любого класса, реализующего интерфейс IdentityInterface
    private static ?IdentityInterface $identity = null;

    // Инициализация класса пользователя
    public static function init(IdentityInterface $identity): void
    {
        self::$identity = $identity;
    }

    // Проверка инициализации
    private static function checkInitialized(): void
    {
        if (!self::$identity) {
            throw new RuntimeException('Auth not initialized. Call Auth::init() first.');
        }
    }

    // Вход пользователя по модели
    public static function login(IdentityInterface $user): void
    {
        self::checkInitialized();
        Session::set('id', $user->getId());
    }

    // Аутентификация пользователя и вход по учетным данным
    public static function attempt(array $credentials): bool
    {
        self::checkInitialized();

        $user = self::$identity->attemptIdentity($credentials);

        if ($user instanceof IdentityInterface) {
            self::login($user);
            return true;
        }

        return false;
    }

    // Возврат текущего аутентифицированного пользователя
    public static function user(): ?IdentityInterface
    {
        self::checkInitialized();

        $id = Session::get('id');
        if (!$id) {
            return null;
        }

        return self::$identity->findIdentity($id);
    }

    // Проверка является ли текущий пользователь аутентифицированным
    public static function check(): bool
    {
        return self::user() !== null;
    }

    // Выход текущего пользователя
    public static function logout(): void
    {
        Session::clear('id');
    }

    // Генерация CSRF-токена
    public static function generateCSRF(): string
    {
        $token = bin2hex(random_bytes(32));
        Session::set('csrf_token', $token);
        return $token;
    }

    // Проверка CSRF-токена
    public static function verifyCSRF(string $token): bool
    {
        $storedToken = Session::get('csrf_token');
        // Удаляем токен только после успешной проверки
        $result = hash_equals($storedToken ?? '', $token);
        if ($result) {
            Session::clear('csrf_token');
        }
        return $result;
    }

    public function getUserFullName()
    {
        if (!$this->check()) {
            return 'Гость';
        }

        $user = $this->user();

        try {
            if ($user->role === 'admin') {
                return $user->name; // Или используйте связанную модель если нужно
            } else {
                $employee = \Model\Employee::where('user_id', $user->id)->first();
                return $employee ? trim("$employee->last_name $employee->first_name $employee->patronym") : 'Сотрудник';
            }
        } catch (\Exception $e) {
            return $user->name ?? 'Пользователь';
        }
    }
}