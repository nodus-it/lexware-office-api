<?php

namespace Nodus\LexwareOfficeApi\Resources;

use Nodus\LexwareOfficeApi\Utils\LexwareOfficeConnector;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\PaginationPlugin\Paginator;
use Spatie\LaravelData\Data;

/**
 * Enhanced base class for all API resources
 * 
 * Provides generic CRUD operations using template pattern
 */
abstract class BaseResource
{
    public function __construct(protected LexwareOfficeConnector $connector) {}

    /**
     * Get the API endpoint for this resource
     */
    abstract protected function getEndpoint(): string;

    /**
     * Get the namespace for request classes
     */
    abstract protected function getRequestNamespace(): string;

    /**
     * Get the data class for this resource
     */
    abstract protected function getDataClass(): string;

    /**
     * Get all items with optional filters
     * 
     * @param array $filters Optional filters to apply
     * @return Paginator
     */
    public function all(array $filters = []): Paginator
    {
        $requestClass = $this->getRequestNamespace() . '\\Get' . $this->getResourceName() . 'sRequest';
        
        if (!class_exists($requestClass)) {
            throw new \InvalidArgumentException("Request class {$requestClass} not found");
        }
        
        return $this->connector->paginate(new $requestClass(...$filters));
    }

    /**
     * Get single item by ID
     * 
     * @param string $id The item ID
     * @return Data
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function get(string $id): Data
    {
        $requestClass = $this->getRequestNamespace() . '\\Get' . $this->getResourceName() . 'Request';
        
        if (!class_exists($requestClass)) {
            throw new \InvalidArgumentException("Request class {$requestClass} not found");
        }
        
        return $this->connector->send(new $requestClass($id))->dtoOrFail();
    }

    /**
     * Create new item
     * 
     * @param Data $data The data to create
     * @return Data
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function create(Data $data): Data
    {
        $requestClass = $this->getRequestNamespace() . '\\Create' . $this->getResourceName() . 'Request';
        
        if (!class_exists($requestClass)) {
            throw new \InvalidArgumentException("Request class {$requestClass} not found");
        }
        
        return $this->connector->send(new $requestClass($data))->dtoOrFail();
    }

    /**
     * Update existing item
     * 
     * @param Data $data The data to update (must include ID)
     * @return Data
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function update(Data $data): Data
    {
        $requestClass = $this->getRequestNamespace() . '\\Update' . $this->getResourceName() . 'Request';
        
        if (!class_exists($requestClass)) {
            throw new \InvalidArgumentException("Request class {$requestClass} not found");
        }
        
        return $this->connector->send(new $requestClass($data))->dtoOrFail();
    }

    /**
     * Delete item by ID
     * 
     * @param string $id The item ID to delete
     * @return mixed
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function delete(string $id): mixed
    {
        $requestClass = $this->getRequestNamespace() . '\\Delete' . $this->getResourceName() . 'Request';
        
        if (!class_exists($requestClass)) {
            throw new \InvalidArgumentException("Request class {$requestClass} not found");
        }
        
        return $this->connector->send(new $requestClass($id));
    }

    /**
     * Get the resource name from class name
     */
    protected function getResourceName(): string
    {
        return str_replace('Resource', '', class_basename($this));
    }

    /**
     * Check if a specific request class exists
     */
    protected function hasRequestClass(string $operation): bool
    {
        $requestClass = $this->getRequestNamespace() . '\\' . ucfirst($operation) . $this->getResourceName() . 'Request';
        return class_exists($requestClass);
    }

    /**
     * Get available operations for this resource
     */
    public function getAvailableOperations(): array
    {
        $operations = ['get', 'create', 'update', 'delete'];
        $available = [];
        
        foreach ($operations as $operation) {
            if ($this->hasRequestClass($operation)) {
                $available[] = $operation;
            }
        }
        
        // Check for list operation (plural)
        if ($this->hasRequestClass('get' . $this->getResourceName() . 's')) {
            $available[] = 'list';
        }
        
        return $available;
    }
}
