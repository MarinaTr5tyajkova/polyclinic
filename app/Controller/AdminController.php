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
        // Проверка прав доступа
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return (new View())->render('errors.403');
        }

        $employeeModel = new Employee();
        $message = null;

        if ($request->method === 'POST') {
            if ($request->has('last_name')) {
                // Добавление нового сотрудника
                $data = [
                    'last_name' => $request->get('last_name'),
                    'first_name' => $request->get('first_name'),
                    'patronym' => $request->get('patronym'),
                    'login' => $request->get('login'),
                    'password' => $request->get('password'),
                    'admin_id' => Auth::user()->id
                ];

                if ($employeeModel->addEmployee($data)) {
                    $message = 'Сотрудник успешно добавлен';
                } else {
                    $message = 'Ошибка при добавлении сотрудника';
                }
            } elseif ($request->has('delete_id')) {
                // Удаление сотрудника
                $deleteId = (int)$request->get('delete_id');
                if ($employeeModel->deleteEmployee($deleteId)) {
                    $message = 'Сотрудник успешно удален';
                } else {
                    $message = 'Ошибка при удалении сотрудника';
                }
            }
        }

        // Получение списка сотрудников
        $employees = $employeeModel->getEmployeesWithUser();

        return (new View())->render('site.employees', [
            'employees' => $employees,
            'message' => $message
        ]);
    }
}