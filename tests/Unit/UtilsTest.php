<?php

namespace Tests\Unit;

use Nodus\LexwareOfficeApi\Requests\Articles\GetArticlesRequest;
use Nodus\LexwareOfficeApi\Utils\LexwareOfficeConnector;
use Nodus\LexwareOfficeApi\Utils\LexwarePaginator;
use Tests\TestCase;

class UtilsTest extends TestCase
{
    public function test_connector_configuration(): void
    {
        config(['lexware-office.auth.token' => 'test-token']);
        config(['lexware-office.rate_limit.store' => 'array']);

        $connector = new LexwareOfficeConnector;

        $this->assertSame('https://api.lexware.io/v1/', $connector->resolveBaseUrl());
        $this->assertSame(30, (function () {
            return $this->connectTimeout;
        })->call($connector));
        $this->assertSame(30, (function () {
            return $this->requestTimeout;
        })->call($connector));

        $auth = (function () {
            return $this->defaultAuth();
        })->call($connector);
        // TokenAuthenticator stores token internally; assert instance type rather than header content
        $this->assertSame('Saloon\\Http\\Auth\\TokenAuthenticator', get_class($auth));

        $store = (function () {
            return $this->resolveRateLimitStore();
        })->call($connector);
        $this->assertStringContainsString('LaravelCacheStore', get_class($store));
    }

    public function test_paginate_returns_custom_paginator(): void
    {
        $connector = new LexwareOfficeConnector;
        $paginator = $connector->paginate(new GetArticlesRequest(null, null, null));

        $this->assertInstanceOf(LexwarePaginator::class, $paginator);
    }
}
