<?php

namespace Middlewares;

use Src\Auth\Auth;
use Src\Request;

class AuthMiddleware
{
    public function handle(Request $request)
    {
        if (!Auth::check()) {
            $_SESSION['message'] = 'Необходимо войти в систему';
            app()->route->redirect('/login');
        }
    }
}
