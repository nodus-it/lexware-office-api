<?php

namespace Nodus\LexwareOfficeApi\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;
use Spatie\LaravelData\Data;

/**
 * Base class for POST/CREATE requests
 */
abstract class BaseCreateRequest extends BaseRequest implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected Data $data) {}

    /**
     * Get request body from data object
     */
    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }
}