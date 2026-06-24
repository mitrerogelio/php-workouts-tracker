<?php

declare(strict_types=1);

namespace App\Services;

use DateTimeImmutable;

/**
 * Type guard for values coming out of the database as `mixed`.
 *
 * These methods do NOT silently coerce — they validate that a value already
 * holds the expected type (or a known DB wire-format, e.g. a tinyint as int,
 * a decimal as a numeric string) and throw if it does not. This surfaces bad
 * data instead of hiding it.
 */
class DataCaster
{
    public static function toString(mixed $value): string
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException('Expected string, got ' . gettype($value));
        }
        return $value;
    }

    public static function toNullableString(mixed $value): ?string
    {
        return $value === null ? null : self::toString($value);
    }

    public static function toInt(mixed $value): int
    {
        if (!is_int($value)) {
            throw new \InvalidArgumentException('Expected int, got ' . gettype($value));
        }
        return $value;
    }

    public static function toNullableInt(mixed $value): ?int
    {
        return $value === null ? null : self::toInt($value);
    }

    /**
     * Accepts float, int, or a numeric string (how PDO returns DECIMAL columns).
     */
    public static function toFloat(mixed $value): float
    {
        if (is_float($value)) {
            return $value;
        }
        if (is_int($value)) {
            return (float) $value;
        }
        if (is_string($value) && is_numeric($value)) {
            return (float) $value;
        }
        throw new \InvalidArgumentException('Expected float, got ' . gettype($value));
    }

    public static function toNullableFloat(mixed $value): ?float
    {
        return $value === null ? null : self::toFloat($value);
    }

    /**
     * Accepts bool, or the int/string forms MySQL uses for tinyint(1).
     */
    public static function toBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }
        if (is_int($value)) {
            return $value !== 0;
        }
        if (is_string($value)) {
            return $value === '1' || strtolower($value) === 'true';
        }
        throw new \InvalidArgumentException('Expected bool, got ' . gettype($value));
    }

    public static function toNullableBool(mixed $value): ?bool
    {
        return $value === null ? null : self::toBool($value);
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function toDateTime(mixed $value): DateTimeImmutable
    {
        return new DateTimeImmutable(self::toString($value));
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function toNullableDateTime(mixed $value): ?DateTimeImmutable
    {
        return $value === null ? null : self::toDateTime($value);
    }
}
