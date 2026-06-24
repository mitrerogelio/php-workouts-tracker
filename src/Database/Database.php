<?php

declare(strict_types=1);

namespace App\Database;

use PDO;

class Database implements IDatabase
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param string $sql
     * @param array<string, mixed> $params
     * @return bool
     */
    public function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * @param string $sql
     * @param array<string, mixed> $params
     * @return array<int, array<string, mixed>>
     * @phpstan-return array<int, array<string, mixed>>
     */
    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        /** @var array<int, array<string, mixed>> */
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return string|bool
     */
    public function getLastInsertId(): string|bool
    {
        return $this->pdo->lastInsertId();
    }
}
