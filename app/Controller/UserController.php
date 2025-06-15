<?php

namespace Controller;

use Src\Request;
use Src\Auth\Auth;
use Src\View;
use Model\User;

class UserController
{
    /**
     * Обработка входа как для веб, так и для API (JSON)
     *
     * @param Request $request
     * @return string|void
     */
    public function login(Request $request)
    {
        // Если GET-запрос — показываем форму входа (веб)
        if ($request->method === 'GET') {
            $message = $_SESSION['message'] ?? null;
            unset($_SESSION['message']);
            return (string) new View('site.login', ['message' => $message]);
        }

        // Определяем, что это API-запрос (например, по заголовку Accept)
        $isApiRequest = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;

        if ($isApiRequest) {
            // API-аутентификация с выдачей токена
            $data = $request->all();

            // Ищем пользователя по логину (замените 'email' на 'login' если нужно)
            $user = User::where('login', $data['login'] ?? '')->first();

            if (!$user || $user->password !== User::hashPassword($data['password'] ?? '')) {
                (new View())->toJSON(['error' => 'Invalid credentials'], 401);
                return;
            }

            // Генерируем токен (метод generateToken должен быть в модели User)
            $token = $user->generateToken();

            (new View())->toJSON(['token' => $token]);
            return;
        }

        // Веб-аутентификация (обычная)
        if (Auth::attempt($request->all())) {
            $user = Auth::user();

            if ($user->isAdmin()) {
                app()->route->redirect('/admin/employees');
            } elseif ($user->isEmployee()) {
                app()->route->redirect('/record');
            }
        }

        return (string) new View('site.login', ['message' => 'Неверные учетные данные']);
    }

    /**
     * Выход пользователя из системы
     */
    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/login');
    }

    /**
     * Защищённый API метод для получения профиля авторизованного пользователя
     */
    public function getProfile(Request $request): void
    {
        // Получаем пользователя из middleware BearerTokenMiddleware
        $user = $request->get('user');

        if (!$user) {
            (new View())->toJSON(['error' => 'Unauthorized'], 401);
            return;
        }

        (new View())->toJSON([
            'id' => $user->id,
            'login' => $user->login,
            'name' => $user->name,
            'role' => $user->role,
            // Добавьте другие необходимые поля
        ]);
    }
}
