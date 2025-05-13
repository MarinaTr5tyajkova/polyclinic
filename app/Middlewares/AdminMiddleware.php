<?php
namespace Middlewares;

use Src\Auth\Auth;
use Src\Request;

class AdminMiddleware
{
    public function handle(Request $request)
    {
        $user = Auth::user();

        // Если пользователь не авторизован или не является администратором
        if (!$user || !$user->isAdmin()) {
            $_SESSION['message'] = 'Доступ только для администраторов';
            app()->route->redirect('/login');
        }
    }
}