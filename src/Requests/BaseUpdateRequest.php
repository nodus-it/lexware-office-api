<?php

namespace Nodus\LexwareOfficeApi\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;
use Spatie\LaravelData\Data;

/**
 * Base class for PUT/UPDATE requests
 */
abstract class BaseUpdateRequest extends BaseRequest implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(protected Data $data) {}

    /**
     * Get request body from data object
     */
    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }

    /**
     * Get the ID from the data object for the endpoint
     */
    protected function getId(): string
    {
        return $this->data->id;
    }
}