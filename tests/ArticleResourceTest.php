<?php

use Nodus\LexwareOfficeApi\Data\Enums\ArticleType;

test('article type enum values', function () {
    expect(ArticleType::PRODUCT->value)->toBe('PRODUCT');
    expect(ArticleType::SERVICE->value)->toBe('SERVICE');
});