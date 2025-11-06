<?php

use Nodus\LexwareOfficeApi\Resources\ArticleResource;
use Nodus\LexwareOfficeApi\Resources\LexwareOfficeResource;
use Nodus\LexwareOfficeApi\Utils\LexwareOfficeConnector;

it('creates a connector and returns article resource', function () {
    $resource = new LexwareOfficeResource;

    expect($resource)->toBeInstanceOf(LexwareOfficeResource::class);

    $articles = $resource->articles();

    expect($articles)
        ->toBeInstanceOf(ArticleResource::class);

    // Ensure internal connector is of expected type via reflection
    $ref = new ReflectionClass($articles);
    $prop = $ref->getProperty('connector');
    $prop->setAccessible(true);
    $connector = $prop->getValue($articles);

    expect($connector)->toBeInstanceOf(LexwareOfficeConnector::class);
});
