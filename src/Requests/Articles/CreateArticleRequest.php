<?php

namespace MeinVendor\MeinPackage\Requests\Articles;

use Nodus\LexwareOfficeApi\Data\ArticleData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Request\CreatesDtoFromResponse;

class CreateArticleRequest extends Request implements HasBody
{
    use CreatesDtoFromResponse;
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(public ArticleData $articleData) {}

    public function resolveEndpoint(): string
    {
        return '/articles';
    }

    protected function defaultBody(): array
    {
        return $this->articleData->toArray();
    }

    public function createDtoFromResponse(Response $response): ArticleData
    {
        return ArticleData::from($response->json());
    }
}
