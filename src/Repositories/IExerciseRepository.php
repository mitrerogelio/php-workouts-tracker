<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Exercise;

interface IExerciseRepository
{
    /**
     * @return array<int, Exercise>
     */
    public function findAll(): array;

    public function findById(int $id): ?Exercise;
}
