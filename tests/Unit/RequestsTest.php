<?php

namespace Tests\Unit;

use Nodus\LexwareOfficeApi\Data\ArticleData;
use Nodus\LexwareOfficeApi\Data\Enums\ArticleType;
use Nodus\LexwareOfficeApi\Data\Enums\LeadingPrice;
use Nodus\LexwareOfficeApi\Requests\Articles\CreateArticleRequest;
use Nodus\LexwareOfficeApi\Requests\Articles\DeleteArticleRequest;
use Nodus\LexwareOfficeApi\Requests\Articles\GetArticleRequest;
use Nodus\LexwareOfficeApi\Requests\Articles\GetArticlesRequest;
use Nodus\LexwareOfficeApi\Requests\Articles\UpdateArticleRequest;
use Tests\TestCase;

class RequestsTest extends TestCase
{
    public function test_resolves_endpoints(): void
    {
        $get = new GetArticleRequest('42');
        $this->assertSame('/articles/42', $get->resolveEndpoint());

        $del = new DeleteArticleRequest('42');
        $this->assertSame('/articles/42', $del->resolveEndpoint());

        $list = new GetArticlesRequest(ArticleType::PRODUCT, 'A-1', '123');
        $this->assertSame('/articles', $list->resolveEndpoint());

        $article = ArticleData::from([
            'id' => '42',
            'title' => 'T',
            'type' => ArticleType::SERVICE,
            'price' => [
                'leadingPrice' => LeadingPrice::NET,
                'netPrice' => '9.99',
            ],
        ]);

        $create = new CreateArticleRequest($article);
        $this->assertSame('/articles', $create->resolveEndpoint());

        $update = new UpdateArticleRequest($article);
        $this->assertSame('/articles/42', $update->resolveEndpoint());
    }

    public function test_default_body_and_query(): void
    {
        $article = ArticleData::from([
            'id' => '42',
            'title' => 'T',
            'type' => ArticleType::SERVICE,
            'price' => [
                'leadingPrice' => LeadingPrice::GROSS,
                'grossPrice' => '12.00',
            ],
        ]);

        $create = new CreateArticleRequest($article);
        $createBody = (function () {
            return $this->defaultBody();
        })->call($create);

        $this->assertIsArray($createBody);
        $this->assertSame('T', $createBody['title']);
        // Enums are serialized to string values in arrays
        $this->assertSame('SERVICE', $createBody['type']);
        $this->assertSame('12.00', $createBody['price']['grossPrice']);

        $list = new GetArticlesRequest(ArticleType::PRODUCT, 'A-1', '123');
        $query = (function () {
            return $this->defaultQuery();
        })->call($list);

        $this->assertIsArray($query);
        $this->assertSame(ArticleType::PRODUCT, $query['type']);
        $this->assertSame('A-1', $query['articleNumber']);
        $this->assertSame('123', $query['gtin']);
    }
    public function test_create_request_create_dto_from_response(): void
    {
        $response = $this->getMockBuilder(\Saloon\Http\Response::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['json'])
            ->getMock();

        $payload = [
            'id' => 'c1',
            'title' => 'Created',
            'type' => ArticleType::PRODUCT,
            'price' => [
                'leadingPrice' => LeadingPrice::NET,
                'netPrice' => '5.00',
            ],
        ];
        $response->method('json')->willReturn($payload);

        $req = new CreateArticleRequest(ArticleData::from($payload));
        $dto = $req->createDtoFromResponse($response);

        $this->assertInstanceOf(ArticleData::class, $dto);
        $this->assertSame('c1', $dto->id);
    }

    public function test_get_request_create_dto_from_response(): void
    {
        $response = $this->getMockBuilder(\Saloon\Http\Response::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['json'])
            ->getMock();

        $payload = [
            'id' => 'g1',
            'title' => 'Got',
            'type' => ArticleType::SERVICE,
            'price' => [
                'leadingPrice' => LeadingPrice::GROSS,
                'grossPrice' => '9.99',
            ],
        ];
        $response->method('json')->willReturn($payload);

        $req = new GetArticleRequest('g1');
        $dto = $req->createDtoFromResponse($response);

        $this->assertSame('g1', $dto->id);
        $this->assertSame('Got', $dto->title);
    }

    public function test_update_request_default_body_and_dto(): void
    {
        $article = ArticleData::from([
            'id' => 'u1',
            'title' => 'Old',
            'type' => ArticleType::PRODUCT,
            'price' => [
                'leadingPrice' => LeadingPrice::NET,
                'netPrice' => '1.23',
            ],
        ]);

        $req = new UpdateArticleRequest($article);

        $body = (function () { return $this->defaultBody(); })->call($req);
        $this->assertSame('Old', $body['title']);
        $this->assertSame('PRODUCT', $body['type']);

        $response = $this->getMockBuilder(\Saloon\Http\Response::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['json'])
            ->getMock();

        $payload = $article->toArray();
        $payload['title'] = 'New';
        $response->method('json')->willReturn($payload);

        $dto = $req->createDtoFromResponse($response);
        $this->assertSame('New', $dto->title);
    }
}
