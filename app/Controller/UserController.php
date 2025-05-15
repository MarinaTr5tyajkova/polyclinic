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
            // FIX: Convert View to string
            return (string) new View('site.login', ['message' => $message]);
        }

        if (Auth::attempt($request->all())) {
            $user = Auth::user();

            if ($user->isAdmin()) {
                app()->route->redirect('/admin/employees');
            } elseif ($user->isEmployee()) {
                app()->route->redirect('/record');
            }
        }

        // FIX: Convert View to string
        return (string) new View('site.login', ['message' => 'Неверные учетные данные']);
    }


    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/login');
    }
}