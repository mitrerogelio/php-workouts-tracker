<?php

declare(strict_types=1);

namespace App\Http;

/**
 * Typed accessors over the $_POST superglobal so controllers never touch
 * `mixed` form data directly.
 */
class Request
{
    public static function postString(string $key, string $default = ''): string
    {
        $value = $_POST[$key] ?? $default;
        return is_string($value) ? trim($value) : $default;
    }

    public static function postInt(string $key, int $default = 0): int
    {
        $value = $_POST[$key] ?? null;
        return is_numeric($value) ? (int) $value : $default;
    }

    public static function postNullableInt(string $key): ?int
    {
        $value = $_POST[$key] ?? null;
        if ($value === null || $value === '') {
            return null;
        }
        return is_numeric($value) ? (int) $value : null;
    }

    public static function postNullableFloat(string $key): ?float
    {
        $value = $_POST[$key] ?? null;
        if ($value === null || $value === '') {
            return null;
        }
        return is_numeric($value) ? (float) $value : null;
    }
}
