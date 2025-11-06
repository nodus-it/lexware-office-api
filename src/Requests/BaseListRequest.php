<?php

namespace Nodus\LexwareOfficeApi\Requests;

use Saloon\Enums\Method;
use Saloon\PaginationPlugin\Contracts\Paginatable;

/**
 * Base class for paginated list requests
 */
abstract class BaseListRequest extends BaseRequest implements Paginatable
{
    protected Method $method = Method::GET;

    /**
     * Apply filters to query parameters
     */
    protected function defaultQuery(): array
    {
        return $this->filterNullValues($this->getFilters());
    }

    /**
     * Get filters for the request
     * Override in child classes to provide specific filters
     */
    protected function getFilters(): array
    {
        return [];
    }
}