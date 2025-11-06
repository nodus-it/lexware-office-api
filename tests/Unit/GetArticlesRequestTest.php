<?php

namespace Tests\Unit;

use Nodus\LexwareOfficeApi\Requests\Articles\GetArticlesRequest;
use Saloon\Http\Response;
use Tests\TestCase;

class GetArticlesRequestTest extends TestCase
{
    public function test_default_query_omits_null_filters(): void
    {
        $req = new GetArticlesRequest(null, null, null);
        $query = (function () { return $this->defaultQuery(); })->call($req);

        $this->assertIsArray($query);
        $this->assertArrayNotHasKey('type', $query);
        $this->assertArrayNotHasKey('articleNumber', $query);
        $this->assertArrayNotHasKey('gtin', $query);
    }

    public function test_create_dto_from_response_returns_collection_array(): void
    {
        $req = new GetArticlesRequest(null, null, null);

        $response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['json'])
            ->getMock();

        $response->method('json')->with('content')->willReturn([]);

        $result = $req->createDtoFromResponse($response);

        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }
}
