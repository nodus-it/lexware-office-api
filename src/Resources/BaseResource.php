<?php

namespace MeinVendor\MeinPackage\Resources;

use Nodus\LexwareOfficeApi\LexwareOfficeConnector;

class BaseResource
{
    public function __construct(protected LexwareOfficeConnector $connector) {}
}
