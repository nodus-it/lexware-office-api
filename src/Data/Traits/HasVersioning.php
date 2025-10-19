<?php

namespace Nodus\LexwareOfficeApi\Data\Traits;

/**
 * Trait for entities with version control
 */
trait HasVersioning
{
    public ?int $version;
    
    /**
     * Check if this entity is newer than another
     */
    public function isNewer(self $other): bool
    {
        if (!$this->version || !$other->version) {
            return false;
        }
        
        return $this->version > $other->version;
    }

    /**
     * Check if this entity is older than another
     */
    public function isOlder(self $other): bool
    {
        if (!$this->version || !$other->version) {
            return false;
        }
        
        return $this->version < $other->version;
    }

    /**
     * Check if versions are the same
     */
    public function isSameVersion(self $other): bool
    {
        return $this->version === $other->version;
    }
}