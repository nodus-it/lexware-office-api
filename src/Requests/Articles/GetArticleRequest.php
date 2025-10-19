<?php

namespace Nodus\LexwareOfficeApi\Requests\Articles;

use Nodus\LexwareOfficeApi\Data\ArticleData;
use Nodus\LexwareOfficeApi\Requests\BaseGetRequest;
use Saloon\Http\Response;

class GetArticleRequest extends BaseGetRequest
{
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
