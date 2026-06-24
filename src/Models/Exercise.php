<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\DataCaster;

class Exercise
{
    public function __construct(
        public int     $id {
            get => $this->id;
        },
        public string  $name {
            get => $this->name;
        },
        public ?string $description {
            get => $this->description;
        },
        public ?bool   $weightRequired {
            get => $this->weightRequired;
        },
    ) {}

    /**
     * Map database row to Domain Model
     * Expected keys: id (int), name (string), description (string|null), weight_required (int|bool|null)
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: DataCaster::toInt($data['id']),
            name: DataCaster::toString($data['name']),
            description: DataCaster::toNullableString($data['description'] ?? null),
            weightRequired: DataCaster::toNullableBool($data['weight_required'] ?? null),
        );
    }
}
