<?php

namespace App\Database;

interface IDatabase
{
    /**
     * @param string $sql
     * @param array<string, mixed> $params
     * @return bool
     */
    public function execute(string $sql, array $params = []): bool;

    /**
     * @param array<string, mixed> $params
     * @return array<int, array<string, mixed>>
     */
    public function query(string $sql, array $params = []): array;

    public function getLastInsertId(): string|bool;
}
