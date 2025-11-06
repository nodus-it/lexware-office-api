<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Nodus\LexwareOfficeApi\Data\ArticleData;
use Nodus\LexwareOfficeApi\Data\ArticlePrice;
use Nodus\LexwareOfficeApi\Data\Enums\ArticleType;
use Nodus\LexwareOfficeApi\Data\Enums\LeadingPrice;
use Tests\TestCase;

class DataTest extends TestCase
{
    public function test_article_price_from_array(): void
    {
        $price = ArticlePrice::from([
            'leadingPrice' => LeadingPrice::NET,
            'netPrice' => '10.50',
            'taxRate' => '19',
        ]);

        $this->assertInstanceOf(ArticlePrice::class, $price);
        $this->assertSame(LeadingPrice::NET, $price->leadingPrice);
        $this->assertSame('10.50', $price->netPrice);
        $this->assertNull($price->grossPrice);
        $this->assertSame('19', $price->taxRate);
    }

    public function test_article_data_from_array_with_casts(): void
    {
        $created = Carbon::now();
        $updated = Carbon::now()->addDay();

        $data = ArticleData::from([
            'id' => 'abc123',
            'title' => 'Test',
            'description' => 'Desc',
            'type' => ArticleType::PRODUCT,
            'articleNumber' => '4711',
            'gtin' => '1234567890123',
            'note' => 'Note',
            'unitName' => 'Stück',
            'price' => [
                'leadingPrice' => LeadingPrice::GROSS,
                'grossPrice' => '12.50',
                'taxRate' => '19',
            ],
            'version' => 1,
            'createdDate' => $created->format('Y-m-d\\TH:i:s.vP'),
            'updatedDate' => $updated->format('Y-m-d\\TH:i:s.vP'),
        ]);

        $this->assertInstanceOf(ArticleData::class, $data);
        $this->assertSame('abc123', $data->id);
        $this->assertSame(ArticleType::PRODUCT, $data->type);
        $this->assertInstanceOf(ArticlePrice::class, $data->price);
        $this->assertSame('12.50', $data->price?->grossPrice);
        $this->assertInstanceOf(Carbon::class, $data->createdDate);
        $this->assertInstanceOf(Carbon::class, $data->updatedDate);

        $array = $data->toArray();
        $this->assertSame('abc123', $array['id']);
        // Enum values are serialized to their string representation in arrays
        $this->assertSame('PRODUCT', $array['type']);
        $this->assertSame('12.50', $array['price']['grossPrice']);
    }
}
