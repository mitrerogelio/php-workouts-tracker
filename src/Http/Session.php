<?php

declare(strict_types=1);

namespace App\Http;

/**
 * Typed wrapper over the $_SESSION superglobal for the current user.
 */
class Session
{
    public static function userId(): ?int
    {
        $value = $_SESSION['user_id'] ?? null;
        return is_int($value) ? $value : null;
    }

    public static function username(): ?string
    {
        $value = $_SESSION['username'] ?? null;
        return is_string($value) ? $value : null;
    }

    public static function login(int $userId, string $username): void
    {
        // Rotate the session id on privilege change to prevent session fixation.
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
    }

    public static function clear(): void
    {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }
}
