<?php

namespace Nodus\LexwareOfficeApi\Requests\Articles;

use Nodus\LexwareOfficeApi\Data\ArticleData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Saloon\Traits\Request\CreatesDtoFromResponse;

class GetArticlesRequest extends Request implements Paginatable
{
    use CreatesDtoFromResponse;

    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/articles';
    }

    protected function defaultQuery(): array
    {
        return [
            // 'type' => 'SERVICE',
            'articleNumber' => null,
            'gtin' => null,

        ];
    }

    public function createDtoFromResponse(Response $response): array
    {
        return ArticleData::collect($response->json('content'));
    }
}
