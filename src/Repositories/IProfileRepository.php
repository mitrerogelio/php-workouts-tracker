<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Gender;
use App\Models\Profile;

interface IProfileRepository
{
    public function findById(int $id): ?Profile;

    public function findByUsername(string $username): ?Profile;

    /**
     * Insert a new profile and return its generated id.
     */
    public function create(string $firstName, string $lastName, string $username, ?Gender $gender): int;
}
