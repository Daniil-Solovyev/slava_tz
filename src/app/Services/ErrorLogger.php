<?php

namespace App\Services;

class ErrorLogger
{
    private $errors = [];
    private const RESULT_PATH = 'logs/result.txt';

    public function addError(int $row_number, array $errors): void
    {
        $this->errors[$row_number] = $errors;
    }

    public function writeErrorsToFile(): void
    {
        $error_messages = [];

        foreach ($this->errors as $row_number => $errors) {
            $error_messages[] = "$row_number - " . implode(', ', $errors);
        }

        file_put_contents(storage_path(self::RESULT_PATH), implode("\n", $error_messages));
    }
}