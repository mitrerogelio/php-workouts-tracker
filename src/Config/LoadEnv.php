<?php

declare(strict_types=1);

namespace App\Config;

class LoadEnv
{
    private string $filePath;

    public function __construct(string $filepath)
    {
        $this->filePath = $filepath;
    }

    public function load(): void
    {
        if (!file_exists($this->filePath)) {
            return;
        }

        $lines = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false) return;

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments
            if (str_starts_with($line, '#')) {
                continue;
            }

            // Split by the first '=' found
            if (str_contains($line, '=')) {
                list($name, $value) = explode('=', $line, 2);

                $name = trim($name);
                $value = trim($value);

                $_ENV[$name] = $value;
            }
        }
    }
}
