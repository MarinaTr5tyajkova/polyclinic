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
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return (new View())->render('errors.403');
        }

        $errors = null;
        $form_data = $request->all();

        if ($request->method === 'POST') {
            if ($request->has('delete_id')) {
                $deleted = Employee::deleteEmployee($request->get('delete_id'));
                header('Location: /polyclinic/admin/employees?message=' . urlencode('Сотрудник удалён'));
                exit;
            } else {
                $result = Employee::createEmployee($form_data, $_FILES['avatar'] ?? null);

                if ($result['success']) {
                    header('Location: /polyclinic/admin/employees?message=' . urlencode('Сотрудник успешно добавлен'));
                    exit;
                } else {
                    $errors = $result['errors']; // Передаём массив ошибок
                }
            }
        }

        // Сообщения успеха из URL (если нужны)
        $message = null;
        if ($request->has('message')) {
            $message = urldecode($request->get('message'));
        }

        $employees = Employee::getEmployeesWithUser();

        return (new View())->render('site.employees', [
            'employees' => $employees,
            'errors' => $errors,
            'message' => $message,
            'form_data' => $form_data,
            'fieldNames' => [
                'last_name' => 'Фамилия',
                'first_name' => 'Имя',
                'login' => 'Логин',
                'password' => 'Пароль'
            ],
        ]);
    }
}
