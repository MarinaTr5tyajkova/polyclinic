<?php

namespace app\Validators;

use Src\Validator\AbstractValidator;

class LoginValidator extends AbstractValidator
{
    protected string $message = 'Логин может содержать только латинские буквы, цифры, символы "_" и "-", и не должно содержать кириллицу';

    public function rule(): bool
    {
        if (preg_match('/[а-яА-ЯЁё]/u', $this->value)) {
            return false;
        }

        return (bool)preg_match('/^[a-zA-Z0-9_-]+$/', $this->value);
    }
}
