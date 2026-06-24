<?php

declare(strict_types=1);

namespace App\Http;

class Redirect
{
    /**
     * Send a Location header and stop execution.
     */
    public static function to(string $path): never
    {
        header('Location: ' . $path);
        exit;
    }
}
