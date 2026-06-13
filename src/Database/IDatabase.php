<?php

namespace App\Database;

interface IDatabase
{
    public function execute(string $sql, array $params = []): bool;
    /**
     * @return array<int, array<string, mixed>>
     */
    public function query(string $sql, array $params = []): array;
    public function getLastInsertId(): string|bool;
}
