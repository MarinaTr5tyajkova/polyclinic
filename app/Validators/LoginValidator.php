<?php

namespace Validators;

use Src\Validator\AbstractValidator;

class LoginValidator extends AbstractValidator
{
    protected string $message = 'Поле ":field" может содержать только латинские буквы, цифры, символы "_" и "-", и не должно содержать кириллицу';

    public function rule(): bool
    {
        return !preg_match('/[^a-zA-Z0-9_-]/', $this->value) === 0;

    }

}
