<?php

declare(strict_types=1);

namespace App\Models;

use DateTimeImmutable;
use App\Services\DataCaster;

class Profile
{
    public function __construct(
        public int                $id {
            get => $this->id;
        },
        public string             $firstName {
            get => $this->firstName;
        },
        public string             $lastName {
            get => $this->lastName;
        },
        public string             $username {
            get => $this->username;
        },
        public ?Gender            $gender {
            get => $this->gender;
        },
        public DateTimeImmutable  $createdAt {
            get => $this->createdAt;
        },
        public ?DateTimeImmutable $updatedAt {
            get => $this->updatedAt;
        },
    )
    {
    }

    /**
     * Map database row to Domain Model
     * Expected keys: id (int), fname, lname, usr_name (string), gender (string|null), created_at (string timestamp), updated_at (string timestamp|null)
     * @param array<string, mixed> $data
     * @throws \DateMalformedStringException
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: DataCaster::toInt($data['id']),
            firstName: DataCaster::toString($data['fname']),
            lastName: DataCaster::toString($data['lname']),
            username: DataCaster::toString($data['usr_name']),
            gender: isset($data['gender']) ? Gender::from(DataCaster::toString($data['gender'])) : null,
            createdAt: new DateTimeImmutable(DataCaster::toString($data['created_at'])),
            updatedAt: isset($data['updated_at']) ? new DateTimeImmutable(DataCaster::toString($data['updated_at'])) : null,
        );
    }
}
