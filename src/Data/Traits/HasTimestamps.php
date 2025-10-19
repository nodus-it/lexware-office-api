<?php

namespace Nodus\LexwareOfficeApi\Data\Traits;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

/**
 * Trait for entities with timestamp fields
 */
trait HasTimestamps
{
    #[WithCast(DateTimeInterfaceCast::class)]
    public ?Carbon $createdDate;

    #[WithCast(DateTimeInterfaceCast::class)]
    public ?Carbon $updatedDate;

    /**
     * Check if entity was created after given date
     */
    public function isCreatedAfter(Carbon $date): bool
    {
        return $this->createdDate && $this->createdDate->isAfter($date);
    }

    /**
     * Check if entity was updated after given date
     */
    public function isUpdatedAfter(Carbon $date): bool
    {
        return $this->updatedDate && $this->updatedDate->isAfter($date);
    }

    /**
     * Get age in days since creation
     */
    public function getAgeInDays(): ?int
    {
        return $this->createdDate ? (int) $this->createdDate->diffInDays(now()) : null;
    }
}