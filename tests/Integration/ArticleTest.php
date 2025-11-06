<?php

use Nodus\LexwareOfficeApi\Data\ArticleData;
use Nodus\LexwareOfficeApi\Data\Enums\ArticleType;
use Nodus\LexwareOfficeApi\LexwareOfficeApi;

// Guard live integration test behind env flag to avoid external calls in CI
$runLive = getenv('LEXWARE_LIVE_TESTS') ?: getenv('RUN_LIVE_TESTS');

$test = test('test article can be created', function () {
    if (! (getenv('LEXWARE_LIVE_TESTS') ?: getenv('RUN_LIVE_TESTS'))) {
        $this->markTestSkipped('Live API tests are disabled. Enable by setting LEXWARE_LIVE_TESTS=1');
    }

    $testArticle = \Tests\Factory\ArticleFactory::make();
    $createdArticle = LexwareOfficeApi::articles()->create($testArticle);

    expect($createdArticle)->toBeInstanceOf(ArticleData::class);

    $getArticle = LexwareOfficeApi::articles()->get($createdArticle->id);
    expect($getArticle)->toBeInstanceOf(ArticleData::class)
        ->and($getArticle->title)->toBe($testArticle->title)
        ->and($getArticle->type)->toBe(ArticleType::PRODUCT)
        ->and($getArticle->unitName)->toBe($testArticle->unitName);

    LexwareOfficeApi::articles()->delete($getArticle->id);
})->group('slow');
