<?php

namespace Nodus\LexwareOfficeApi\Utils;

use Nodus\LexwareOfficeApi\Resources\BaseResource;
use InvalidArgumentException;

/**
 * Factory for creating API resource instances
 */
class ResourceFactory
{
    /**
     * Mapping of resource names to their classes
     */
    private static array $resourceMap = [
        'articles' => \Nodus\LexwareOfficeApi\Resources\ArticleResource::class,
        // Additional entities will be registered here
        // 'contacts' => \Nodus\LexwareOfficeApi\Resources\ContactResource::class,
        // 'invoices' => \Nodus\LexwareOfficeApi\Resources\InvoiceResource::class,
        // 'vouchers' => \Nodus\LexwareOfficeApi\Resources\VoucherResource::class,
    ];

    /**
     * Create a resource instance
     * 
     * @param string $resourceName The resource name (e.g., 'articles')
     * @param LexwareOfficeConnector $connector The connector instance
     * @return BaseResource
     * @throws InvalidArgumentException
     */
    public static function create(string $resourceName, LexwareOfficeConnector $connector): BaseResource
    {
        if (!isset(self::$resourceMap[$resourceName])) {
            throw new InvalidArgumentException("Resource '{$resourceName}' not found. Available resources: " . implode(', ', array_keys(self::$resourceMap)));
        }

        $resourceClass = self::$resourceMap[$resourceName];
        
        if (!class_exists($resourceClass)) {
            throw new InvalidArgumentException("Resource class '{$resourceClass}' does not exist");
        }

        return new $resourceClass($connector);
    }

    /**
     * Register a new resource
     * 
     * @param string $name The resource name
     * @param string $resourceClass The resource class
     * @throws InvalidArgumentException
     */
    public static function register(string $name, string $resourceClass): void
    {
        if (!class_exists($resourceClass)) {
            throw new InvalidArgumentException("Resource class '{$resourceClass}' does not exist");
        }

        if (!is_subclass_of($resourceClass, BaseResource::class)) {
            throw new InvalidArgumentException("Resource class '{$resourceClass}' must extend BaseResource");
        }

        self::$resourceMap[$name] = $resourceClass;
    }

    /**
     * Unregister a resource
     * 
     * @param string $name The resource name
     */
    public static function unregister(string $name): void
    {
        unset(self::$resourceMap[$name]);
    }

    /**
     * Get all registered resources
     * 
     * @return array
     */
    public static function getRegisteredResources(): array
    {
        return self::$resourceMap;
    }

    /**
     * Check if a resource is registered
     * 
     * @param string $name The resource name
     * @return bool
     */
    public static function isRegistered(string $name): bool
    {
        return isset(self::$resourceMap[$name]);
    }

    /**
     * Get available resource names
     * 
     * @return array
     */
    public static function getAvailableResources(): array
    {
        return array_keys(self::$resourceMap);
    }

    /**
     * Bulk register resources from array
     * 
     * @param array $resources Array of name => class mappings
     */
    public static function registerBulk(array $resources): void
    {
        foreach ($resources as $name => $class) {
            self::register($name, $class);
        }
    }
}