<?php

namespace Tests\Unit;

use Nodus\LexwareOfficeApi\Utils\LexwareOfficeConnector;
use Nodus\LexwareOfficeApi\Utils\LexwarePaginator;
use PHPUnit\Framework\MockObject\MockObject;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Tests\TestCase;

class PaginatorTest extends TestCase
{
    /** @var LexwareOfficeConnector|MockObject */
    private $connector;

    /** @var Request */
    private $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->connector = $this->getMockBuilder(LexwareOfficeConnector::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->request = new class extends \Nodus\LexwareOfficeApi\Requests\Articles\GetArticlesRequest {
            public function __construct() { parent::__construct(null, null, null); }
        };
    }

    public function test_apply_pagination_adds_page_query(): void
    {
        $paginator = new LexwarePaginator($this->connector, $this->request);

        $apply = (new \ReflectionClass($paginator))->getMethod('applyPagination');
        $apply->setAccessible(true);
        $apply->invoke($paginator, $this->request);

        $current = (function () { return $this->getCurrentPage(); })->call($paginator);
        $this->assertSame($current, $this->request->query()->get('page'));
    }

    public function test_is_last_page_and_get_items_delegates_to_response(): void
    {
        $paginator = new LexwarePaginator($this->connector, $this->request);

        $response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['json', 'dto'])
            ->getMock();

        $response->method('json')->with('last')->willReturn(true);
        $response->method('dto')->willReturn([['id' => 1], ['id' => 2]]);

        $isLast = (function () use ($response) {
            return $this->isLastPage($response);
        })->call($paginator);

        $items = (function () use ($response) {
            return $this->getPageItems($response, $this->request);
        })->call($paginator);

        $this->assertTrue($isLast);
        $this->assertCount(2, $items);
        $this->assertSame(1, $items[0]['id']);
    }
}
