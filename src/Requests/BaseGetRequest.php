<?php

namespace Nodus\LexwareOfficeApi\Requests;

use Saloon\Enums\Method;

/**
 * Base class for GET requests
 */
abstract class BaseGetRequest extends BaseRequest
{
    protected Method $method = Method::GET;
}