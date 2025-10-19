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

/**
 * Article resource for managing articles in Lexware Office API
 * 
 * @see https://developers.lexoffice.io/docs/#articles-endpoint
 */
class ArticleResource extends BaseResource
{
    /**
     * Get the API endpoint for this resource
     */
    protected function getEndpoint(): string
    {
        return 'articles';
    }

    /**
     * Get the namespace for request classes
     */
    protected function getRequestNamespace(): string
    {
        return 'Nodus\\LexwareOfficeApi\\Requests\\Articles';
    }

    /**
     * Get the data class for this resource
     */
    protected function getDataClass(): string
    {
        return ArticleData::class;
    }

    /**
     * Get all articles with optional filters
     * 
     * @param array $filters Array of filters (supports filterType, filterArticleNumber, filterGtin)
     * @return Paginator
     */
    public function all(array $filters = []): Paginator
    {
        return $this->connector->paginate(new GetArticlesRequest(
            $filters['filterType'] ?? null,
            $filters['filterArticleNumber'] ?? null,
            $filters['filterGtin'] ?? null
        ));
    }

    /**
     * Get all articles with typed parameters (convenience method)
     * 
     * @param ArticleType|null $filterType Filter by article type
     * @param string|null $filterArticleNumber Filter by article number
     * @param string|null $filterGtin Filter by GTIN
     * @return Paginator
     */
    public function allWithFilters(
        ?ArticleType $filterType = null,
        ?string      $filterArticleNumber = null,
        ?string      $filterGtin = null
    ): Paginator
    {
        return $this->all([
            'filterType' => $filterType,
            'filterArticleNumber' => $filterArticleNumber,
            'filterGtin' => $filterGtin
        ]);
    }

    /**
     * Get a single article by ID
     * 
     * @param string $id The article ID
     * @return ArticleData
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function get(string $id): ArticleData
    {
        return $this->connector->send(new GetArticleRequest($id))->dtoOrFail();
    }

    /**
     * Create a new article
     *
     * @see https://developers.lexoffice.io/docs/#articles-endpoint-create-an-article
     *
     * @param Data $data The article data to create
     * @return Data
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function create(Data $data): Data
    {
        return $this->connector->send(new CreateArticleRequest($data))->dtoOrFail();
    }

    /**
     * Create a new article with typed parameter (convenience method)
     *
     * @param ArticleData $articleData The article data to create
     * @return ArticleData
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function createArticle(ArticleData $articleData): ArticleData
    {
        return $this->create($articleData);
    }

    /**
     * Update an existing article
     * 
     * @param Data $data The article data to update (must include ID)
     * @return Data
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function update(Data $data): Data
    {
        return $this->connector->send(new UpdateArticleRequest($data))->dtoOrFail();
    }

    /**
     * Update an existing article with typed parameter (convenience method)
     * 
     * @param ArticleData $articleData The article data to update (must include ID)
     * @return ArticleData
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function updateArticle(ArticleData $articleData): ArticleData
    {
        return $this->update($articleData);
    }

    /**
     * Delete an article by ID
     * 
     * @param string $id The article ID to delete
     * @return mixed
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function delete(string $id): mixed
    {
        return $this->connector->send(new DeleteArticleRequest($id));
    }

    /**
     * Find articles by article number
     * 
     * @param string $articleNumber The article number to search for
     * @return Paginator
     */
    public function findByArticleNumber(string $articleNumber): Paginator
    {
        return $this->all(filterArticleNumber: $articleNumber);
    }

    /**
     * Find articles by GTIN
     * 
     * @param string $gtin The GTIN to search for
     * @return Paginator
     */
    public function findByGtin(string $gtin): Paginator
    {
        return $this->all(filterGtin: $gtin);
    }

    /**
     * Find articles by type
     * 
     * @param ArticleType $type The article type to filter by
     * @return Paginator
     */
    public function findByType(ArticleType $type): Paginator
    {
        return $this->all(filterType: $type);
    }
}
