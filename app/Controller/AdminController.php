<?php

namespace Controller;

use Src\Request;
use Src\View;
use Model\Employee;
use Src\Auth\Auth;
use Src\Validator\Validator;

class AdminController
{
    public function employees(Request $request): string
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return (new View())->render('errors.403');
        }

        $message = null;
        $errors = [];

        if ($request->method === 'POST') {
            $avatarFileName = null;

            // Обработка загрузки аватара
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/avatars/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $tmpName = $_FILES['avatar']['tmp_name'];
                $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $avatarFileName = uniqid('avatar_', true) . '.' . $extension;
                move_uploaded_file($tmpName, $uploadDir . $avatarFileName);
            } else {
                $avatarFileName = 'default-avatar.svg';
            }

            if ($request->has('last_name')) {
                // Правила валидации
                $validator = new Validator($request->all(), [
                    'last_name' => ['required'],
                    'first_name' => ['required'],
                    'login' => ['required', 'login', 'unique:users,login'],
                    'password' => ['required', 'password'],
                ], [
                    'required' => 'Поле :field обязательно для заполнения',
                    'login' => 'Логин может содержать только латинские буквы, цифры, символы "_" и "-", и не должен содержать кириллицу',
                    'password' => 'Пароль должен содержать минимум 6 символов, включая как минимум одну заглавную букву и одну цифру',
                    'unique' => 'Логин уже используется',
                ]);

                if ($validator->fails()) {
                    $errors = $validator->errors();
                } else {
                    $result = Employee::addEmployee($request, $avatarFileName);
                    $message = $result ? 'Сотрудник успешно добавлен' : 'Ошибка при добавлении сотрудника';
                }
            } elseif ($request->has('delete_id')) {
                $message = Employee::deleteEmployee($request->get('delete_id'))
                    ? 'Сотрудник успешно удален'
                    : 'Ошибка при удалении сотрудника';
            }
        }

        // Массив для отображения человекочитаемых названий полей
        $fieldNames = [
            'last_name' => 'Фамилия',
            'first_name' => 'Имя',
            'login' => 'Логин',
            'password' => 'Пароль',
        ];

        return (new View())->render('site.employees', [
            'employees' => Employee::getEmployeesWithUser(),
            'message' => $message,
            'errors' => $errors,
            'fieldNames' => $fieldNames,
        ]);
    }
}
