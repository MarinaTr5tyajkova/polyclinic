<?php
namespace Middlewares;

use Src\Auth\Auth;
use Src\Request;

class EmployeeMiddleware
{
    public function handle(Request $request)
    {
        $user = Auth::user();

        // Если пользователь не авторизован или не является сотрудником
        if (!$user || !$user->isEmployee()) {
            $_SESSION['message'] = 'Доступ только для сотрудников';
            app()->route->redirect('/login');
        }
    }
}