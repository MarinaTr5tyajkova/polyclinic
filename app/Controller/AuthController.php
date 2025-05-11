<?php

namespace Controller;

use Src\Auth\Auth;
use Src\Request;
use Src\View;

class AuthController
{
    public function login(Request $request): string
    {
        if ($request->method === 'GET') {
            return new View('auth.login');
        }

        if (Auth::attempt($request->all())) {
            $user = Auth::user();
            if ($user->isAdmin()) {
                app()->route->redirect('/admin');
            } elseif ($user->isRegistrar()) {
                app()->route->redirect('/registrar');
            } else {
                app()->route->redirect('/'); // Для обычных пользователей
            }
        }

        return new View('auth.login', ['message' => 'Неправильные логин или пароль']);
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/');
    }
}