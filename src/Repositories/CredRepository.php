<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\IDatabase;
use App\Models\Cred;

class CredRepository implements ICredRepository
{
    public function __construct(private IDatabase $db) {}

    public function findByEmail(string $email): ?Cred
    {
        $rows = $this->db->query('SELECT * FROM creds WHERE email = :email', ['email' => $email]);
        return $rows === [] ? null : Cred::fromArray($rows[0]);
    }

    public function findByUserId(int $usrId): ?Cred
    {
        $rows = $this->db->query('SELECT * FROM creds WHERE usr_id = :usrId', ['usrId' => $usrId]);
        return $rows === [] ? null : Cred::fromArray($rows[0]);
    }

    public function create(int $usrId, string $email, string $passHash, string $role): void
    {
        $this->db->execute(
            'INSERT INTO creds (usr_id, email, pass_hash, role) VALUES (:usrId, :email, :passHash, :role)',
            [
                'usrId' => $usrId,
                'email' => $email,
                'passHash' => $passHash,
                'role' => $role,
            ]
        );
    }

    public function updateLastLogin(int $usrId): void
    {
        $this->db->execute(
            'UPDATE creds SET last_login = NOW() WHERE usr_id = :usrId',
            ['usrId' => $usrId]
        );
    }
}
