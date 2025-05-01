<?php

namespace Nodus\LexwareOfficeApi\Requests\Articles;

use Nodus\LexwareOfficeApi\Data\ArticleData;
use Nodus\LexwareOfficeApi\Data\Enums\ArticleType;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Saloon\Traits\Request\CreatesDtoFromResponse;

class GetArticlesRequest extends Request implements Paginatable
{
    use CreatesDtoFromResponse;

    protected Method $method = Method::GET;

    public function __construct(
        protected ?ArticleType $filterType,
        protected ?string      $filterArticleNumber,
        protected ?string      $filterGtin)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/articles';
    }

    protected function defaultQuery(): array
    {
        return [
            'type' => $this->filterType,
            'articleNumber' => $this->filterArticleNumber,
            'gtin' => $this->filterGtin,

        ];
    }

    public function createDtoFromResponse(Response $response): array
    {
        return ArticleData::collect($response->json('content'));
    }
}
