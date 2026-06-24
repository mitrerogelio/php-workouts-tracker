<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\DataCaster;

class ExerciseSet
{
    public function __construct(
        public int    $id {
            get => $this->id;
        },
        public int    $sessionId {
            get => $this->sessionId;
        },
        public int    $exerciseId {
            get => $this->exerciseId;
        },
        public ?int   $reps {
            get => $this->reps;
        },
        public ?float $weight {
            get => $this->weight;
        },
        public ?int   $duration {
            get => $this->duration;
        },
        public int    $setNumber {
            get => $this->setNumber;
        },
    ) {}

    /**
     * Map database row to Domain Model
     * Expected keys: id, session_id, exercise_id, set_number (int),
     *                reps (int|null), weight (numeric-string|null), duration (int|null)
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: DataCaster::toInt($data['id']),
            sessionId: DataCaster::toInt($data['session_id']),
            exerciseId: DataCaster::toInt($data['exercise_id']),
            reps: DataCaster::toNullableInt($data['reps'] ?? null),
            weight: DataCaster::toNullableFloat($data['weight'] ?? null),
            duration: DataCaster::toNullableInt($data['duration'] ?? null),
            setNumber: DataCaster::toInt($data['set_number']),
        );
    }
}
