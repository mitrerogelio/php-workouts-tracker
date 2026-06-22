<?php

namespace App\Models;

use DateTimeImmutable;

class Profile
{
    public function __construct(
        protected int             $id {
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
            id: (int)$data['id'],
            firstName: (string)$data['fname'],
            lastName: (string)$data['lname'],
            username: (string)$data['usr_name'],
            gender: isset($data['gender']) ? Gender::from((string)$data['gender']) : null,
            createdAt: new DateTimeImmutable((string)$data['created_at']),
            updatedAt: isset($data['updated_at']) ? new DateTimeImmutable((string)$data['updated_at']) : null,
        );
    }
}
