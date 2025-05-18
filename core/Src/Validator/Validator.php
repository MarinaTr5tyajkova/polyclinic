<?php

namespace Src\Validator;

use Src\Validator\AbstractValidator;

class Validator
{
    protected array $data;
    protected array $rules;
    protected array $messages;
    protected array $errors = [];

    public function __construct(array $data, array $rules, array $messages = [])
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->messages = $messages;
    }

    public function validate(): bool
    {
        foreach ($this->rules as $field => $fieldRules) {
            $value = $this->data[$field] ?? null;

            foreach ($fieldRules as $rule) {
                $validator = $this->makeValidator($rule, $field, $value);

                $result = $validator->validate();
                if ($result !== true) {
                    $errorMessage = $this->messages[$rule] ?? $result;
                    $this->errors[$field][] = $errorMessage;
                }
            }
        }
        return empty($this->errors);
    }

    protected function makeValidator(string $rule, string $field, $value): AbstractValidator
    {
        if (strpos($rule, ':') !== false) {
            [$ruleName, $params] = explode(':', $rule, 2);
            $params = explode(',', $params);
        } else {
            $ruleName = $rule;
            $params = [];
        }

        switch ($ruleName) {
            case 'required':
                return new \App\Validators\RequireValidator($field, $value);
            case 'unique':
                return new \App\Validators\UniqueValidator($field, $value, $params);
            case 'password':
                return new \App\Validators\PasswordValidator($field, $value);
            case 'login':
                return new \App\Validators\LoginValidator($field, $value);
            case 'age':  // Добавляем сюда
                return new \App\Validators\AgeValidator($field, $value, $params);
            default:
                throw new \InvalidArgumentException("Правило валидации {$ruleName} не поддерживается");
        }
    }



    public function fails(): bool
    {
        return !$this->validate();
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
