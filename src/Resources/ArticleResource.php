<?php

namespace MeinVendor\MeinPackage\Resources;

use MeinVendor\MeinPackage\Requests\Articles\CreateArticleRequest;
use MeinVendor\MeinPackage\Requests\Articles\DeleteArticleRequest;
use MeinVendor\MeinPackage\Requests\Articles\GetArticleRequest;
use MeinVendor\MeinPackage\Requests\Articles\GetArticlesRequest;
use MeinVendor\MeinPackage\Requests\Articles\UpdateArticleRequest;
use Nodus\LexwareOfficeApi\Data\ArticleData;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\PaginationPlugin\Paginator;

class ArticleResource extends BaseResource
{
    public function all(): Paginator
    {
        return $this->connector->paginate(new GetArticlesRequest());
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function get(string $id): ArticleData{
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
    public function create(ArticleData $articleData): ArticleData{
        return $this->connector->send(new CreateArticleRequest($articleData))->dtoOrFail();
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function update(ArticleData $articleData){
        return $this->connector->send(new UpdateArticleRequest($articleData))->dtoOrFail();
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function delete(string $articleNumber){
        return $this->connector->send(new DeleteArticleRequest($articleNumber))->dtoOrFail();
    }
}
