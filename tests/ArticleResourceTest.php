<?php

namespace Nodus\LexwareOfficeApi\Tests;

use Nodus\LexwareOfficeApi\Data\ArticleData;
use Nodus\LexwareOfficeApi\Data\Enums\ArticleType;
use Nodus\LexwareOfficeApi\Exceptions\LexwareOfficeException;
use Nodus\LexwareOfficeApi\Resources\ArticleResource;

class ArticleResourceTest extends LexwareOfficeTestCase
{
    private ArticleResource $articles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->articles = new ArticleResource($this->connector);
    }

    public function test_can_get_article()
    {
        // Mock successful response
        $this->mockClient->addResponse(
            $this->mockSuccessResponse($this->getSampleArticleData())
        );
        
        $article = $this->articles->get('article-123');
        
        $this->assertEquals('Sample Article', $article->title);
        $this->assertEquals('PROD-001', $article->articleNumber);
        $this->assertRequestSent('GET', 'articles/article-123');
    }

    public function test_can_create_article()
    {
        $articleData = ArticleData::from([
            'title' => 'New Product',
            'articleNumber' => 'PROD-002',
            'description' => 'Product description',
            'type' => ArticleType::PRODUCT,
            'unitName' => 'Stück',
        ]);

        $this->mockClient->addResponse(
            $this->mockSuccessResponse(array_merge(
                $articleData->toArray(),
                ['id' => 'new-article-id']
            ))
        );

        $createdArticle = $this->articles->create($articleData);

        $this->assertEquals('new-article-id', $createdArticle->id);
        $this->assertEquals('New Product', $createdArticle->title);
        $this->assertRequestSent('POST', 'articles');
    }

    public function test_can_update_article()
    {
        $articleData = ArticleData::from([
            'id' => 'article-123',
            'title' => 'Updated Product',
            'articleNumber' => 'PROD-001',
            'version' => 1,
        ]);

        $this->mockClient->addResponse(
            $this->mockSuccessResponse(array_merge(
                $articleData->toArray(),
                ['version' => 2]
            ))
        );

        $updatedArticle = $this->articles->update($articleData);

        $this->assertEquals('Updated Product', $updatedArticle->title);
        $this->assertEquals(2, $updatedArticle->version);
        $this->assertRequestSent('PUT', 'articles/article-123');
    }

    public function test_can_delete_article()
    {
        $this->mockClient->addResponse($this->mockSuccessResponse(null, 204));

        $response = $this->articles->delete('article-123');

        $this->assertRequestSent('DELETE', 'articles/article-123');
    }

    public function test_can_list_articles()
    {
        $this->mockClient->addResponse(
            $this->mockPaginatedResponse([
                $this->getSampleArticleData(),
                $this->getSampleArticleData(['id' => 'article-456', 'title' => 'Another Article'])
            ])
        );

        $paginator = $this->articles->all();
        $articles = $paginator->items();

        $this->assertCount(2, $articles);
        $this->assertInstanceOf(ArticleData::class, $articles[0]);
        $this->assertRequestSent('GET', 'articles');
    }

    public function test_can_filter_articles_by_type()
    {
        $this->mockClient->addResponse(
            $this->mockPaginatedResponse([
                $this->getSampleArticleData(['type' => ArticleType::SERVICE])
            ])
        );

        $paginator = $this->articles->findByType(ArticleType::SERVICE);
        $articles = $paginator->items();

        $this->assertCount(1, $articles);
        $this->assertEquals(ArticleType::SERVICE, $articles[0]->type);
        $this->assertRequestSent('GET', 'articles', ['type' => 'SERVICE']);
    }

    public function test_handles_validation_errors()
    {
        $this->mockClient->addResponse(
            $this->mockValidationErrorResponse([
                'title' => ['Title is required'],
                'articleNumber' => ['Article number must be unique']
            ])
        );

        $this->expectException(LexwareOfficeException::class);

        $articleData = ArticleData::from([]);
        $this->articles->create($articleData);
    }

    public function test_handles_not_found_error()
    {
        $this->mockClient->addResponse(
            $this->mockErrorResponse('Article not found', 404)
        );

        $this->expectException(LexwareOfficeException::class);

        $this->articles->get('non-existent-id');
    }

    private function getSampleArticleData(array $overrides = []): array
    {
        return array_merge([
            'id' => 'article-123',
            'organizationId' => 'org-456',
            'title' => 'Sample Article',
            'description' => 'Sample description',
            'type' => ArticleType::PRODUCT,
            'articleNumber' => 'PROD-001',
            'gtin' => '1234567890123',
            'note' => 'Sample note',
            'unitName' => 'Stück',
            'price' => [
                'currency' => 'EUR',
                'netAmount' => 19.99,
                'grossAmount' => 23.79,
                'taxRatePercentage' => 19.0,
            ],
            'version' => 1,
            'createdDate' => '2023-01-01T10:00:00Z',
            'updatedDate' => '2023-01-01T10:00:00Z',
        ], $overrides);
    }
}