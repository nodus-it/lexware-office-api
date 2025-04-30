<?php

namespace Nodus\LexwareOfficeApi\Resources;

use Nodus\LexwareOfficeApi\Utils\LexwareOfficeConnector;

class BaseResource
{
    public function __construct(protected LexwareOfficeConnector $connector) {}
}
