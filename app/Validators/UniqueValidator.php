<?php

namespace App\Validators;

use Illuminate\Database\Capsule\Manager as Capsule;
use Src\Validator\AbstractValidator;

class UniqueValidator extends AbstractValidator
{
    protected string $message = 'Поле :field должно быть уникальным';

    public function __construct(string $fieldName, $value, array $args = [], string $message = null)
    {
        parent::__construct($fieldName, $value, $args, $message);
        if (count($args) < 2) {
            throw new \InvalidArgumentException('Для unique валидатора необходимо указать таблицу и поле');
        }
    }

    public function rule(): bool
    {
        return Capsule::table($this->args[0])
                ->where($this->args[1], $this->value)
                ->count() === 0;
    }
}
