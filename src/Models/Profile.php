<?php

namespace App\Models;

use DateTimeImmutable;
use Exception;

class Profile
{
    /**
     * @param int $id
     * @param string $firstName
     * @param string $lastName
     * @param string $username
     * @param Gender|null $gender
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable|null $updatedAt
     */
    public function __construct(
        int $id,
        string $firstName,
        string $lastName,
        string $username,
        ?Gender $gender,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
        $this->gender = $gender;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * Map database row to Domain Model
     * @param array<string, mixed> $data
     * @throws Exception
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (int) $data['id'],
            (string) $data['fname'],
            (string) $data['lname'],
            (string) $data['usr_name'],
            isset($data['gender']) ? Gender::from((string)$data['gender']) : null,
            new DateTimeImmutable((string)$data['created_at']),
            isset($data['updated_at']) ? new DateTimeImmutable((string)$data['updated_at']) : null
        );
    }

    public int $id {
        get => $this->id;
    }

    public string $firstName {
        get => $this->firstName;
    }

    public string $lastName {
        get => $this->lastName;
    }

    public string $username {
        get => $this->username;
    }

    public ?Gender $gender {
        get => $this->gender;
    }

    public DateTimeImmutable $createdAt {
        get => $this->createdAt;
    }

    public ?DateTimeImmutable $updatedAt {
        get => $this->updatedAt;
    }
}
