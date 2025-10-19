<?php

namespace Nodus\LexwareOfficeApi\Requests\Articles;

use Nodus\LexwareOfficeApi\Data\ArticleData;
use Nodus\LexwareOfficeApi\Requests\BaseUpdateRequest;
use Saloon\Http\Response;

class UpdateArticleRequest extends BaseUpdateRequest
{
    public function __construct(public ArticleData $articleData) {}

    public function resolveEndpoint(): string
    {
        return '/articles/'.$this->articleData->id;
    }

    protected function defaultBody(): array
    {
        return $this->articleData->toApiArray();
    }

    public function createDtoFromResponse(Response $response): ArticleData
    {
        return ArticleData::from($response->json());
    }
}
