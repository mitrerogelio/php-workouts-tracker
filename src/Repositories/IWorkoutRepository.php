<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\WorkoutSession;

interface IWorkoutRepository
{
    public function findById(int $id): ?WorkoutSession;

    /**
     * @return array<int, WorkoutSession>
     */
    public function findByUserId(int $userId): array;

    /**
     * Insert a new workout session and return its generated id.
     */
    public function create(int $userId, ?string $notes): int;

    /**
     * Update an existing session's name/notes.
     */
    public function update(int $id, ?string $notes): bool;

    public function delete(int $id): bool;
}
