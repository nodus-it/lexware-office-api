<?php

namespace Tests\Factory;

use Nodus\LexwareOfficeApi\Data\ArticleData;
use Nodus\LexwareOfficeApi\Data\Enums\ArticleType;

class ArticleFactory extends Factory
{
    public static function make(): ArticleData
    {
        $faker = \Faker\Factory::create();

        return ArticleData::from([
            'title' => $faker->userName,
            'description' => $faker->text,
            'type' => $faker->randomElement(ArticleType::cases()),
            'articleNumber' => $faker->numberBetween(10000, 50000),
            'gtin' => $faker->ean13(),
            'note' => $faker->text,
            'unitName' => $faker->randomElement(['Stück', 'Meter', 'Kilogramm']),
            'price' => ArticlePriceFactory::make()
        ]);
    }
}
