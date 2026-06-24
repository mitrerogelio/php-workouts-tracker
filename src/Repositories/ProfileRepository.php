<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\IDatabase;
use App\Models\Gender;
use App\Models\Profile;

class ProfileRepository implements IProfileRepository
{
    public function __construct(private IDatabase $db) {}

    public function findById(int $id): ?Profile
    {
        $rows = $this->db->query('SELECT * FROM profiles WHERE id = :id', ['id' => $id]);
        return $rows === [] ? null : Profile::fromArray($rows[0]);
    }

    public function findByUsername(string $username): ?Profile
    {
        $rows = $this->db->query('SELECT * FROM profiles WHERE usr_name = :username', ['username' => $username]);
        return $rows === [] ? null : Profile::fromArray($rows[0]);
    }

    public function create(string $firstName, string $lastName, string $username, ?Gender $gender): int
    {
        $this->db->execute(
            'INSERT INTO profiles (fname, lname, usr_name, gender) VALUES (:fname, :lname, :username, :gender)',
            [
                'fname' => $firstName,
                'lname' => $lastName,
                'username' => $username,
                'gender' => $gender?->value,
            ]
        );

        $id = $this->db->getLastInsertId();
        return is_string($id) ? (int) $id : 0;
    }
}
