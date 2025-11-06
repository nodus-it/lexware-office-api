<?php

namespace Nodus\LexwareOfficeApi\Requests;

use Saloon\Enums\Method;

/**
 * Base class for DELETE requests
 */
abstract class BaseDeleteRequest extends BaseRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(protected string $id) {}

    /**
     * Get the ID for the endpoint
     */
    protected function getId(): string
    {
        return $this->id;
    }

    /**
     * Override DTO creation for delete requests (usually no response body)
     */
    protected function getDataClass(): string
    {
        return ''; // Delete requests typically don't return data
    }
}