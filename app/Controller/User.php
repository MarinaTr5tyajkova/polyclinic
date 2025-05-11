<?php

namespace Controller;

use Src\Auth\Auth;
use Src\Request;
use Src\View;

class User
{
    public function login(Request $request): string
    {
        if ($request->method === 'GET') {
            return (string) new View('site.login');
        }

        if (Auth::attempt($request->all())) {
            app()->route->redirect('/hello');
        }

        return (string) new View('site.login', ['message' => 'Неправильные логин или пароль']);
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/hello');
    }
}