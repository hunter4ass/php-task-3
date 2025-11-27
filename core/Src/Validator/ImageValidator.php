<?php

namespace Src\Validator;

class ImageValidator extends AbstractValidator
{
    protected string $message = 'Поле :field должно содержать изображение допустимого формата';

    public function rule(): bool
    {
        if (!$this->value || !is_array($this->value)) {
            return true;
        }

        if (($this->value['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return true;
        }

        if (($this->value['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            return false;
        }

        $allowedExtensions = $this->collectExtensions();
        $maxSizeKb = $this->collectMaxSize();

        $extension = strtolower(pathinfo($this->value['name'] ?? '', PATHINFO_EXTENSION));
        if ($allowedExtensions && !in_array($extension, $allowedExtensions, true)) {
            return false;
        }

        if ($maxSizeKb && ($this->value['size'] ?? 0) > $maxSizeKb * 1024) {
            $this->message = 'Поле :field должно быть изображением не более ' . $maxSizeKb . ' КБ';
            return false;
        }

        $mime = mime_content_type($this->value['tmp_name']);
        if ($mime === false || strpos($mime, 'image/') !== 0) {
            return false;
        }

        return true;
    }

    private function collectExtensions(): array
    {
        return array_values(array_filter(array_map(function ($arg) {
            return is_numeric($arg) ? null : strtolower($arg);
        }, $this->args)));
    }

    private function collectMaxSize(): ?int
    {
        foreach ($this->args as $arg) {
            if (is_numeric($arg)) {
                return (int)$arg;
            }
        }
        return null;
    }
}


