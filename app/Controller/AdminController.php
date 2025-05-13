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

        $employeeModel = new Employee();
        $message = null;

        if ($request->method === 'POST') {
            if ($request->has('last_name')) {
                // Add new employee
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
                }
            } elseif ($request->has('delete_id')) {
                if ($employeeModel->deleteEmployee((int)$request->get('delete_id'))) {
                    $message = 'Сотрудник успешно удален';
                }
            }
        }

        $employees = $employeeModel->getEmployeesWithUser();

        return (new View())->render('site.employees', [
            'employees' => $employees,
            'message' => $message
        ]);
    }
}