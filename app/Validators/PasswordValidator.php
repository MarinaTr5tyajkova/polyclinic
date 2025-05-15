<?php

namespace Validators;

use Src\Validator\AbstractValidator;

class PasswordValidator extends AbstractValidator
{
    protected string $message = 'Поле ":field" должно содержать минимум 6 символов, включая как минимум одну заглавную букву и одну цифру';

    public function rule(): bool
    {
        // Проверка пароля: минимум 6 символов, хотя бы одна заглавная и одна цифра
        return (bool)preg_match('/^(?=.*[A-Z])(?=.*\d).{6,}$/', $this->value);
    }
}
