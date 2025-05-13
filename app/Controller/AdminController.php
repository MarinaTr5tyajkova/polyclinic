<?php
namespace Controller;

use Src\Request;
use Src\View;
use Model\Employee;
use Src\Auth\Auth;

class AdminController
{
    public function employees(Request $request): string
    {
        // Проверяем права доступа
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return (new View())->render('errors.403');
        }

        $message = null;

        if ($request->method === 'POST') {
            if ($request->has('last_name')) {
                // Добавляем сотрудника
                $result = Employee::addEmployee($request);
                $message = $result ? 'Сотрудник успешно добавлен' : 'Ошибка при добавлении сотрудника';
            } elseif ($request->has('delete_id')) {
                // Удаляем сотрудника
                $message = Employee::deleteEmployee($request->get('delete_id'))
                    ? 'Сотрудник успешно удален'
                    : 'Ошибка при удалении сотрудника';
            }
        }

        // Возвращаем представление
        return (new View())->render('site.employees', [
            'employees' => Employee::getEmployeesWithUser(),
        ]);
    }
}