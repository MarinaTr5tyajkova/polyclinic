<?php

namespace Middlewares;

use Src\Request;
use Src\View;
use Model\ApiToken;
use Src\Auth\Auth;

class BearerTokenMiddleware
{
    public function handle(Request $request): Request
    {
        $header = $request->headers['Authorization'] ?? '';

        if (!preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            (new View())->toJSON(['error' => 'Unauthorized: Bearer token not found'], 401);
            exit;
        }

        $token = $matches[1];

        $apiToken = ApiToken::where('token', $token)
            ->where('expires_at', '>', date('Y-m-d H:i:s'))
            ->first();

        if (!$apiToken) {
            (new View())->toJSON(['error' => 'Unauthorized: Invalid or expired token'], 401);
            exit;
        }

        // Авторизуем пользователя в приложении
        Auth::login($apiToken->user);

        // Добавляем пользователя в объект запроса
        $request->set('user', $apiToken->user);

        return $request;
    }
}
