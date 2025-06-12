<?php

use Nodus\LexwareOfficeApi\Data\ArticleData;
use Nodus\LexwareOfficeApi\Data\Enums\ArticleType;
use Nodus\LexwareOfficeApi\LexwareOfficeApi;

test('test article can be created', function () {
    $testArticle = \Tests\Factory\ArticleFactory::make();
    $createdArticle = LexwareOfficeApi::articles()->create($testArticle);

    expect($createdArticle)->toBeInstanceOf(ArticleData::class);

    $getArticle = LexwareOfficeApi::articles()->get($createdArticle->id);
    expect($getArticle)->toBeInstanceOf(ArticleData::class)
        ->and($getArticle->title)->toBe($testArticle->title)
        ->and($getArticle->type)->toBe(ArticleType::PRODUCT)
        ->and($getArticle->unitName)->toBe($testArticle->unitName);

    LexwareOfficeApi::articles()->delete($getArticle->id);
});
