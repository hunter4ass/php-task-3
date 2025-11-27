<?php

namespace Src\Validator;

class DateAfterValidator extends AbstractValidator
{
    protected string $message = 'Поле :field должно быть не ранее :date';

    public function __construct(string $fieldName, $value, $args = [], string $message = null)
    {
        parent::__construct($fieldName, $value, $args, $message);
        $this->messageKeys[':date'] = $this->resolveLabel($args[0] ?? 'today');
    }

    public function rule(): bool
    {
        if (empty($this->value)) {
            return true;
        }

        $valueTs = strtotime((string)$this->value);
        $target = $this->args[0] ?? 'today';
        $targetTs = $this->resolveDate($target);

        if ($valueTs === false || $targetTs === false) {
            return false;
        }

        return $valueTs >= $targetTs;
    }

    private function resolveDate(string $target)
    {
        if ($target === 'today') {
            return strtotime(date('Y-m-d'));
        }
        return strtotime($target);
    }

    private function resolveLabel(string $target): string
    {
        if ($target === 'today') {
            return 'сегодняшней даты';
        }
        return $target;
    }
}


