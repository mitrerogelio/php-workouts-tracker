<?php

declare(strict_types=1);

namespace App\Services;

use App\Database\Database;
use App\Database\IDatabase;
use PDO;

class DatabaseFactory
{
    /**
     * Build a configured database connection from environment variables.
     */
    public function createDatabase(): IDatabase
    {
        $host = $this->env('DB_HOST', '127.0.0.1');
        $port = $this->env('DB_PORT', '3306');
        $name = $this->env('DB_NAME', '');
        $user = $this->env('DB_USER', '');
        $pass = $this->env('DB_PASS', '');

        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Return native column types (int for INT, etc.) instead of strings.
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return new Database($pdo);
    }

    private function env(string $key, string $default): string
    {
        $value = $_ENV[$key] ?? $default;
        return is_string($value) ? $value : $default;
    }
}
