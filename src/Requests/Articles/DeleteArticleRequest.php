<?php

namespace Nodus\LexwareOfficeApi\Requests\Articles;

use Nodus\LexwareOfficeApi\Requests\BaseDeleteRequest;

class DeleteArticleRequest extends BaseDeleteRequest
{
    public function __construct(protected string $id) {}

    public function resolveEndpoint(): string
    {
        return '/articles/'.$this->id;
    }
}
