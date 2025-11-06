<?php

namespace Nodus\LexwareOfficeApi\Requests\Articles;

use Nodus\LexwareOfficeApi\Data\ArticleData;
use Nodus\LexwareOfficeApi\Data\Enums\ArticleType;
use Nodus\LexwareOfficeApi\Requests\BaseListRequest;
use Saloon\Http\Response;

class GetArticlesRequest extends BaseListRequest
{
    public function __construct(
        protected ?ArticleType $filterType,
        protected ?string      $filterArticleNumber,
        protected ?string      $filterGtin)
    {
    }

    protected function getDataClass(): string
    {
        return ArticleData::class;
    }

    public function resolveEndpoint(): string
    {
        return '/articles';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'type' => $this->filterType?->value,
            'articleNumber' => $this->filterArticleNumber,
            'gtin' => $this->filterGtin,
        ]);
    }

    public function createDtoFromResponse(Response $response): array
    {
        return ArticleData::collect($response->json('content'));
    }
}
