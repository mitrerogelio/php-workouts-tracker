<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\IDatabase;
use App\Models\WorkoutSession;

class WorkoutRepository implements IWorkoutRepository
{
    public function __construct(private IDatabase $db) {}

    public function findById(int $id): ?WorkoutSession
    {
        $rows = $this->db->query('SELECT * FROM workout_sessions WHERE id = :id', ['id' => $id]);
        return $rows === [] ? null : WorkoutSession::fromArray($rows[0]);
    }

    /**
     * @return array<int, WorkoutSession>
     */
    public function findByUserId(int $userId): array
    {
        $rows = $this->db->query(
            'SELECT * FROM workout_sessions WHERE usr_id = :userId ORDER BY created_at DESC',
            ['userId' => $userId]
        );

        return array_map(
            static fn (array $row): WorkoutSession => WorkoutSession::fromArray($row),
            $rows
        );
    }

    public function create(int $userId, ?string $notes): int
    {
        $this->db->execute(
            'INSERT INTO workout_sessions (usr_id, notes) VALUES (:userId, :notes)',
            ['userId' => $userId, 'notes' => $notes]
        );

        $id = $this->db->getLastInsertId();
        return is_string($id) ? (int) $id : 0;
    }

    public function delete(int $id): bool
    {
        return $this->db->execute('DELETE FROM workout_sessions WHERE id = :id', ['id' => $id]);
    }
}
