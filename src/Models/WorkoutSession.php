<?php

declare(strict_types=1);

namespace App\Models;

use DateTimeImmutable;
use App\Services\DataCaster;

class WorkoutSession
{

    public function __construct(
        public int                $id {
            get => $this->id;
        },
        public int                $userId {
            get => $this->userId;
        },
        public DateTimeImmutable  $createdAt {
            get => $this->createdAt;
        },
        public ?string            $notes = null {
            get => $this->notes;
        },
    ) {}

    /**
     * Map database row to Domain Model
     * Expected keys: id (int), usr_id (int), created_at (string timestamp), notes (string|null)
     * @param array<string, mixed> $data
     * @throws \DateMalformedStringException
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: DataCaster::toInt($data['id']),
            userId: DataCaster::toInt($data['usr_id']),
            createdAt: new DateTimeImmutable(DataCaster::toString($data['created_at'])),
            notes: isset($data['notes']) ? DataCaster::toString($data['notes']) : null,
        );
    }
}
