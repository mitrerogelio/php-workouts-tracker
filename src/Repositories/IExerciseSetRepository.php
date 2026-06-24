<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ExerciseSet;

interface IExerciseSetRepository
{
    /**
     * @return array<int, ExerciseSet>
     */
    public function findBySessionId(int $sessionId): array;

    public function findById(int $id): ?ExerciseSet;

    /**
     * Insert a new set for a session and return its generated id.
     */
    public function create(
        int $sessionId,
        int $exerciseId,
        ?int $reps,
        ?float $weight,
        ?int $duration,
        int $setNumber
    ): int;

    /**
     * Update an existing set (the parent session is not changed).
     */
    public function update(
        int $id,
        int $exerciseId,
        ?int $reps,
        ?float $weight,
        ?int $duration,
        int $setNumber
    ): bool;

    public function delete(int $id): bool;
}
