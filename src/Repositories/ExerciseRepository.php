<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\IDatabase;
use App\Models\Exercise;

class ExerciseRepository implements IExerciseRepository
{
    public function __construct(private IDatabase $db) {}

    /**
     * @return array<int, Exercise>
     */
    public function findAll(): array
    {
        $rows = $this->db->query('SELECT * FROM exercises ORDER BY name ASC');

        return array_map(
            static fn (array $row): Exercise => Exercise::fromArray($row),
            $rows
        );
    }

    public function findById(int $id): ?Exercise
    {
        $rows = $this->db->query('SELECT * FROM exercises WHERE id = :id', ['id' => $id]);
        return $rows === [] ? null : Exercise::fromArray($rows[0]);
    }
}
