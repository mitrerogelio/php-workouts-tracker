<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Cred;

interface ICredRepository
{
    public function findByEmail(string $email): ?Cred;

    public function findByUserId(int $usrId): ?Cred;

    public function create(int $usrId, string $email, string $passHash, string $role): void;

    public function updateLastLogin(int $usrId): void;
}
