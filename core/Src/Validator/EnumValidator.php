<?php

namespace Src\Validator;

class EnumValidator extends AbstractValidator
{
    protected string $message = 'Поле :field содержит недопустимое значение';

    public function rule(): bool
    {
        if ($this->value === null || $this->value === '') {
            return true;
        }

        return in_array((string)$this->value, $this->args, true);
    }
}


