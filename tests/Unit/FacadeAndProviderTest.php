<?php

namespace Tests\Unit;

use Nodus\LexwareOfficeApi\LexwareOfficeApi;
use Nodus\LexwareOfficeApi\Resources\ArticleResource;
use Nodus\LexwareOfficeApi\Resources\LexwareOfficeResource;
use Tests\TestCase;

class FacadeAndProviderTest extends TestCase
{
    public function test_service_provider_binds_singleton(): void
    {
        $instance = app()->make('lexware-office-api');
        $this->assertInstanceOf(LexwareOfficeResource::class, $instance);
    }

    public function test_facade_resolves_resource_and_articles(): void
    {
        $resource = LexwareOfficeApi::getFacadeRoot();
        $this->assertInstanceOf(LexwareOfficeResource::class, $resource);

        $articles = LexwareOfficeApi::articles();
        $this->assertInstanceOf(ArticleResource::class, $articles);
    }
}
