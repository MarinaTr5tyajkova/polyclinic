<?php

namespace Controller\Api;

use Model\User;
use Src\Request;
use Src\View;
use Src\Auth\Auth;
use Src\Validator\Validator;

class AuthController
{
    // Регистрация пользователя
    public function register(Request $request): void
    {
        $data = $request->all();

        $validator = new Validator($data, [
            'login' => ['required', 'unique:users,login'],
            'password' => ['required', 'min:6']
        ]);

        if ($validator->fails()) {
            (new View())->toJSON(['errors' => $validator->errors()], 422);
            return;
        }

        $user = User::create([
            'login' => $data['login'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'name' => $data['name'] ?? '',
            'lastName' => $data['lastName'] ?? ''
        ]);

        if ($user) {
            $token = Auth::generateToken($user->id);
            (new View())->toJSON([
                'message' => 'Пользователь зарегистрирован',
                'token' => $token
            ], 201);
        } else {
            (new View())->toJSON(['error' => 'Не удалось создать пользователя'], 500);
        }
    }

    // Вход пользователя
    public function login(Request $request): void
    {
        $credentials = $request->all();

        if (!$user = Auth::attempt($credentials)) {
            (new View())->toJSON(['error' => 'Неправильный логин или пароль'], 401);
            return;
        }

        $token = Auth::generateToken($user->id);

        (new View())->toJSON([
            'message' => 'Авторизация успешна',
            'token' => $token,
            'user' => $user->only(['id', 'login', 'name', 'lastName'])
        ]);
    }

    // Пример защищенного метода, доступного только с токеном
    public function secureData(Request $request): void
    {
        $user = $request->get('user'); // Получаем пользователя из middleware

        (new View())->toJSON([
            'message' => 'Вы успешно вошли через токен!',
            'user' => $user->only(['id', 'login', 'name', 'lastName'])
        ]);
    }
}
