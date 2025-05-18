<?php
namespace App\Validators;

use Src\Validator\AbstractValidator;

class AgeValidator extends AbstractValidator
{
    protected int $minAge;

    public function __construct(string $field, $value, array $args = [], string $message = null)
    {
        parent::__construct($field, $value, $args, $message);
        $this->minAge = isset($args[0]) ? (int)$args[0] : 18;
        $this->message = $this->message ?: "Возраст должен быть не менее :min лет";
    }

    public function rule(): bool
    {
        if (empty($this->value)) {
            return false; // Можно считать, что пустое значение невалидно
        }

        $birthday = \DateTime::createFromFormat('Y-m-d', $this->value);
        if (!$birthday) {
            return false;
        }

        $today = new \DateTime();
        $age = $today->diff($birthday)->y;

        if ($age < $this->minAge) {
            // Подменяем плейсхолдер :min в сообщении
            $this->messageKeys[':min'] = $this->minAge;
            return false;
        }

        return true;
    }
}
