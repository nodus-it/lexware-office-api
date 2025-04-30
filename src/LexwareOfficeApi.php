<?php

namespace Nodus\LexwareOfficeApi;

use Illuminate\Support\Facades\Facade;
use Nodus\LexwareOfficeApi\Resources\LexwareOfficeResource;

/**
 * @mixin LexwareOfficeResource
 */
class LexwareOfficeApi extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return 'lexware-office-api';
    }
}
