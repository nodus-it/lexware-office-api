<?php

namespace Tests\Unit;

use Nodus\LexwareOfficeApi\Data\ArticleData;
use Nodus\LexwareOfficeApi\Data\Enums\ArticleType;
use Nodus\LexwareOfficeApi\Data\Enums\LeadingPrice;
use Nodus\LexwareOfficeApi\Requests\Articles\DeleteArticleRequest;
use Nodus\LexwareOfficeApi\Requests\Articles\GetArticlesRequest;
use Nodus\LexwareOfficeApi\Resources\ArticleResource;
use Nodus\LexwareOfficeApi\Utils\LexwareOfficeConnector;
use Nodus\LexwareOfficeApi\Utils\LexwarePaginator;
use PHPUnit\Framework\MockObject\MockObject;
use Saloon\Http\Response;
use Tests\TestCase;

class ArticleResourceTest extends TestCase
{
    /** @var LexwareOfficeConnector|MockObject */
    private $connector;

    private ArticleResource $resource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->connector = $this->getMockBuilder(LexwareOfficeConnector::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['send', 'paginate'])
            ->getMock();

        $this->resource = new ArticleResource($this->connector);
    }

    public function test_all_uses_connector_paginate_and_returns_paginator(): void
    {
        // Prepare a real paginator instance to be returned (must be Paginatable)
        $request = new \Nodus\LexwareOfficeApi\Requests\Articles\GetArticlesRequest(null, null, null);

        $paginator = new LexwarePaginator($this->connector, $request);

        $this->connector->expects($this->once())
            ->method('paginate')
            ->with($this->callback(function ($req) {
                return $req instanceof GetArticlesRequest;
            }))
            ->willReturn($paginator);

        $result = $this->resource->all([
            'filterType' => ArticleType::PRODUCT,
            'filterArticleNumber' => 'A-42',
            'filterGtin' => '123',
        ]);

        $this->assertSame($paginator, $result);
    }

    public function test_get_uses_connector_send_and_maps_dto(): void
    {
        $article = (object) [
            'id' => 'id-1',
            'title' => 'T',
            'type' => ArticleType::SERVICE,
            'price' => (object) [
                'leadingPrice' => LeadingPrice::NET,
                'netPrice' => '1.00',
            ],
        ];

        $response = new class($article) {
            public function __construct(private $dto) {}
            public function dtoOrFail() { return $this->dto; }
        };

        $this->connector->expects($this->once())
            ->method('send')
            ->with($this->callback(function ($req) {
                return (string)method_exists($req, 'resolveEndpoint') && str_contains($req->resolveEndpoint(), '/articles/');
            }))
            ->willReturn($response);

        $result = $this->resource->get('id-1');
        $this->assertInstanceOf(ArticleData::class, $result);
        $this->assertSame('id-1', $result->id);
    }

    public function test_create_uses_connector_send_and_returns_article(): void
    {
        $input = ArticleData::from([
            'title' => 'New',
            'type' => ArticleType::PRODUCT,
            'price' => [
                'leadingPrice' => LeadingPrice::GROSS,
                'grossPrice' => '12.00',
            ],
        ]);

        $created = ArticleData::from(array_merge($input->toArray(), ['id' => 'new-id']));

        $response = new class($created) {
            public function __construct(private $dto) {}
            public function dtoOrFail() { return $this->dto; }
        };

        $this->connector->expects($this->once())
            ->method('send')
            ->willReturn($response);

        $result = $this->resource->create($input);
        $this->assertSame('new-id', $result->id);
    }

    public function test_update_uses_connector_send_and_returns_article(): void
    {
        $input = ArticleData::from([
            'id' => 'u1',
            'title' => 'Upd',
            'type' => ArticleType::PRODUCT,
            'price' => [
                'leadingPrice' => LeadingPrice::NET,
                'netPrice' => '3.50',
            ],
        ]);

        $updated = ArticleData::from(array_merge($input->toArray(), ['title' => 'Updated']));

        $response = new class($updated) {
            public function __construct(private $dto) {}
            public function dtoOrFail() { return $this->dto; }
        };

        $this->connector->expects($this->once())
            ->method('send')
            ->willReturn($response);

        $result = $this->resource->update($input);
        $this->assertSame('Updated', $result->title);
    }

    public function test_delete_uses_connector_send_and_returns_response(): void
    {
        $response = $this->getMockBuilder(Response::class)->disableOriginalConstructor()->getMock();

        $this->connector->expects($this->once())
            ->method('send')
            ->with($this->callback(fn ($req) => $req instanceof DeleteArticleRequest))
            ->willReturn($response);

        $result = $this->resource->delete('deadbeef');
        $this->assertSame($response, $result);
    }
}
