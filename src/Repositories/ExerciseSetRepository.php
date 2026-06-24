<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\IDatabase;
use App\Models\ExerciseSet;

class ExerciseSetRepository implements IExerciseSetRepository
{
    public function __construct(private IDatabase $db) {}

    /**
     * @return array<int, ExerciseSet>
     */
    public function findBySessionId(int $sessionId): array
    {
        $rows = $this->db->query(
            'SELECT * FROM exercise_sets WHERE session_id = :sessionId ORDER BY set_number ASC',
            ['sessionId' => $sessionId]
        );

        return array_map(
            static fn (array $row): ExerciseSet => ExerciseSet::fromArray($row),
            $rows
        );
    }

    public function create(
        int $sessionId,
        int $exerciseId,
        ?int $reps,
        ?float $weight,
        ?int $duration,
        int $setNumber
    ): int {
        $this->db->execute(
            'INSERT INTO exercise_sets (session_id, exercise_id, reps, weight, duration, set_number)
             VALUES (:sessionId, :exerciseId, :reps, :weight, :duration, :setNumber)',
            [
                'sessionId' => $sessionId,
                'exerciseId' => $exerciseId,
                'reps' => $reps,
                'weight' => $weight,
                'duration' => $duration,
                'setNumber' => $setNumber,
            ]
        );

        $id = $this->db->getLastInsertId();
        return is_string($id) ? (int) $id : 0;
    }
}
