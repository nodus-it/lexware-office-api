<?php

namespace Nodus\LexwareOfficeApi\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

/**
 * Enhanced base class for all data objects
 * 
 * Provides validation, transformation and common functionality
 */
abstract class BaseData extends Data
{
    /**
     * Get validation rules for creation
     */
    public static function createRules(ValidationContext $context): array
    {
        return [];
    }

    /**
     * Get validation rules for updates
     */
    public static function updateRules(ValidationContext $context): array
    {
        return [];
    }

    /**
     * Transform data before sending to API
     * Override in child classes for custom transformations
     */
    public function toApiArray(): array
    {
        return $this->toArray();
    }

    /**
     * Transform data after receiving from API
     * Override in child classes for custom transformations
     */
    public static function fromApiArray(array $data): static
    {
        return static::from($data);
    }

    /**
     * Check if entity has an ID (exists in API)
     */
    public function exists(): bool
    {
        return isset($this->id) && !empty($this->id);
    }

    /**
     * Get a summary representation of the entity
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id ?? null,
            'type' => class_basename($this),
        ];
    }
}
