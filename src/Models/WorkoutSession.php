<?php

namespace App\Models;

use DateTimeImmutable;

class WorkoutSession
{

    /**
     * @param int $id
     * @param int $userId
     * @param DateTimeImmutable $createdAt
     * @param ?string $notes
     */

    public function __construct(
        public int                $id {
            get => $this->id;
        },
        public int                $userId {
            get => $this->userId;
            set => throw new \LogicException('id is readonly');
        },
        public DateTimeImmutable $createdAt {
            get => $this->createdAt;
        },
        public ?string            $notes = null {
            get => $this->notes;
        },
    )
    {
    }

    /**
     * Map database row to Domain Model
     * Expected keys: id (int), usr_id (int), created_at (string timestamp), notes (string|null)
     * @param array<string, mixed> $data
     * @throws \DateMalformedStringException
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int)$data['id'],
            userId: (int)$data['usr_id'],
            createdAt: new DateTimeImmutable((string)$data['created_at']),
            notes: isset($data['notes']) ? (string)$data['notes'] : null
        );
    }
}
