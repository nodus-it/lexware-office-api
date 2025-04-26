<?php

namespace MeinVendor\MeinPackage\Requests\Articles;

use Nodus\LexwareOfficeApi\Data\ArticleData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Saloon\Traits\Request\CreatesDtoFromResponse;

class GetArticleRequest extends Request implements Paginatable
{
    use CreatesDtoFromResponse;

    protected Method $method = Method::GET;

    public function __construct(protected string $id) {}

    public function resolveEndpoint(): string
    {
        return '/articles/'.$this->id;
    }

    public function createDtoFromResponse(Response $response): ArticleData
    {
        return ArticleData::from($response->json());
    }
}
