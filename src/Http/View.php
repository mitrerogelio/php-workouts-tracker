<?php

declare(strict_types=1);

namespace App\Http;

class View
{
    /**
     * Render a template from src/Views with the given data in scope.
     *
     * @param array<string, mixed> $data
     */
    public static function render(string $template, array $data = []): void
    {
        $templatePath = __DIR__ . '/../Views/' . $template . '.php';

        if (!is_file($templatePath)) {
            throw new \RuntimeException("View not found: {$template}");
        }

        (static function (string $__path, array $__data): void {
            extract($__data, EXTR_SKIP);
            require $__path;
        })($templatePath, $data);
    }

    /**
     * Escape a value for safe HTML output.
     */
    public static function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}
