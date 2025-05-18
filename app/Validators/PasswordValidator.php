<?php

namespace App\Validators;

use Src\Validator\AbstractValidator;

class PasswordValidator extends AbstractValidator
{
    protected string $message = 'Пароль должен содержать минимум 6 символов, одну заглавную букву (латинскую или кириллическую) и одну цифру';

    public function rule(): bool
    {
        $value = $this->value;

        if (mb_strlen($value) < 6) {
            return false;
        }

        // Проверяем наличие заглавной буквы (латинской или кириллической) с поддержкой UTF-8
        if (!preg_match('/\p{Lu}/u', $value)) {
            return false;
        }

        // Проверяем наличие цифры
        if (!preg_match('/\d/', $value)) {
            return false;
        }

        return true;
    }
}
