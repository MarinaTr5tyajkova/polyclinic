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

        $employees = Employee::getEmployeesWithUser() ?? [];
        $message = null;
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
                    $message = json_encode($result['errors'], JSON_UNESCAPED_UNICODE);
                }
            }
        }

        // Добавляем обработку сообщений из URL
        if ($request->has('message')) {
            $message = json_encode(['success' => urldecode($request->get('message'))], JSON_UNESCAPED_UNICODE);
        }

        return (new View())->render('site.employees', [
            'employees' => $employees,
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
