<?php

namespace Src\Validator;

class PhoneValidator extends AbstractValidator
{
    protected string $message = 'Поле :field должно содержать корректный номер телефона';

    public function rule(): bool
    {
        if ($this->value === null || $this->value === '') {
            return true;
        }

        return (bool)preg_match('/^\+?[0-9\s\-\(\)]{10,20}$/u', (string)$this->value);
    }
}


