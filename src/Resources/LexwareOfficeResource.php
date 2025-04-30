<?php

namespace Nodus\LexwareOfficeApi\Resources;

use Nodus\LexwareOfficeApi\Utils\LexwareOfficeConnector;

class LexwareOfficeResource
{
    protected LexwareOfficeConnector $connector;

    public function __construct()
    {
        $this->connector = new LexwareOfficeConnector();
    }

    public function articles(): ArticleResource
    {
        return new ArticleResource($this->connector);
    }
}
