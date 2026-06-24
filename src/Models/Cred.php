<?php

declare(strict_types=1);

namespace App\Models;

use DateTimeImmutable;
use App\Services\DataCaster;

/**
 * Authentication credentials for a Profile (creds table).
 * Keyed by usr_id, which is both PK and FK to profiles.id.
 */
class Cred
{
    public function __construct(
        public int                $usrId {
            get => $this->usrId;
        },
        public ?string            $email {
            get => $this->email;
        },
        public ?string            $phone {
            get => $this->phone;
        },
        public string             $passHash {
            get => $this->passHash;
        },
        public ?DateTimeImmutable $lastLogin {
            get => $this->lastLogin;
        },
        public ?DateTimeImmutable $createdAt {
            get => $this->createdAt;
        },
        public string             $role {
            get => $this->role;
        },
        public bool               $twoFactorEnabled {
            get => $this->twoFactorEnabled;
        },
    ) {}

    /**
     * Map database row to Domain Model
     * Expected keys: usr_id (int), email/phone (string|null), pass_hash (string),
     *                last_login/created_at (timestamp|null), role (string|null), 2fa_enabled (int|null)
     * @param array<string, mixed> $data
     * @throws \DateMalformedStringException
     */
    public static function fromArray(array $data): self
    {
        return new self(
            usrId: DataCaster::toInt($data['usr_id']),
            email: DataCaster::toNullableString($data['email'] ?? null),
            phone: DataCaster::toNullableString($data['phone'] ?? null),
            passHash: DataCaster::toString($data['pass_hash']),
            lastLogin: DataCaster::toNullableDateTime($data['last_login'] ?? null),
            createdAt: DataCaster::toNullableDateTime($data['created_at'] ?? null),
            role: DataCaster::toString($data['role'] ?? 'guest'),
            twoFactorEnabled: DataCaster::toBool($data['2fa_enabled'] ?? false),
        );
    }
}
