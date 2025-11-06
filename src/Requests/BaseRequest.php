<?php

namespace Nodus\LexwareOfficeApi\Requests;

use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Request\CreatesDtoFromResponse;

/**
 * Abstract base class for all Lexware Office API requests
 * 
 * Provides common functionality for DTO creation and response handling
 */
abstract class BaseRequest extends Request
{
    use CreatesDtoFromResponse;

    /**
     * Get the data class for DTO creation
     */
    abstract protected function getDataClass(): string;
    
    /**
     * Create DTO from API response
     * 
     * Handles both single items and collections automatically
     */
    public function createDtoFromResponse(Response $response): mixed
    {
        $dataClass = $this->getDataClass();
        
        if ($this->isCollectionResponse($response)) {
            return $dataClass::collect($response->json('content'));
        }
        
        return $dataClass::from($response->json());
    }
    
    /**
     * Determine if response contains a collection
     */
    protected function isCollectionResponse(Response $response): bool
    {
        return $response->json('content') !== null;
    }

    /**
     * Filter null values from query parameters
     */
    protected function filterNullValues(array $query): array
    {
        return array_filter($query, fn($value) => $value !== null);
    }
}