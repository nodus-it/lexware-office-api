<?php

namespace Nodus\LexwareOfficeApi\Resources;

use Nodus\LexwareOfficeApi\Data\ArticleData;
use Nodus\LexwareOfficeApi\Data\Enums\ArticleType;
use Nodus\LexwareOfficeApi\Requests\Articles\CreateArticleRequest;
use Nodus\LexwareOfficeApi\Requests\Articles\DeleteArticleRequest;
use Nodus\LexwareOfficeApi\Requests\Articles\GetArticleRequest;
use Nodus\LexwareOfficeApi\Requests\Articles\GetArticlesRequest;
use Nodus\LexwareOfficeApi\Requests\Articles\UpdateArticleRequest;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\PaginationPlugin\Paginator;

class ArticleResource extends BaseResource
{
    public function all(
        ?ArticleType $filterType = null,
        ?string      $filterArticleNumber = null,
        ?string      $filterGtin = null
    ): Paginator
    {
        return $this->connector->paginate(new GetArticlesRequest(
            $filterType,
            $filterArticleNumber,
            $filterGtin
        ));
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function get(string $id): ArticleData
    {
        return $this->connector->send(new GetArticleRequest($id))->dtoOrFail();
    }

    /**
     * Creates a new article
     *
     * @see https://developers.lexoffice.io/docs/#articles-endpoint-create-an-article
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function create(ArticleData $articleData): ArticleData
    {
        return $this->connector->send(new CreateArticleRequest($articleData))->dtoOrFail();
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function update(ArticleData $articleData): ArticleData
    {
        return $this->connector->send(new UpdateArticleRequest($articleData))->dtoOrFail();
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function delete(string $id)
    {
        return $this->connector->send(new DeleteArticleRequest($id));
    }
}
