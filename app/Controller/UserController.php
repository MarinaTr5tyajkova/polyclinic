<?php

namespace Controller;

use Src\Request;
use Src\Auth\Auth;
use Src\View;

class UserController
{
    public function login(Request $request): string
    {
        if ($request->method === 'GET') {
            $message = $_SESSION['message'] ?? null;
            unset($_SESSION['message']);
            return new View('site.login', ['message' => $message]); // Используем шаблон admin.login
        }

        if (Auth::attempt($request->all())) {
            $user = Auth::user();

            // Редирект в зависимости от роли
            if ($user->isAdmin()) {
                app()->route->redirect('/admin/employees');
            } elseif ($user->isEmployee()) {
                app()->route->redirect('/record');
            }
        }

        return new View('site.login', ['message' => 'Неверные учетные данные']);
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/login');
    }
}