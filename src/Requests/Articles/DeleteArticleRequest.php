<?php

namespace Nodus\LexwareOfficeApi\Requests\Articles;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Request\CreatesDtoFromResponse;

class DeleteArticleRequest extends Request
{
    use CreatesDtoFromResponse;

    protected Method $method = Method::DELETE;

    public function __construct(protected string $id) {}

    public function resolveEndpoint(): string
    {
        return '/articles/'.$this->id;
    }
}
