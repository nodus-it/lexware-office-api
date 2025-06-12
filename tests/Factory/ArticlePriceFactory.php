<?php

namespace Tests\Factory;

use Nodus\LexwareOfficeApi\Data\ArticlePrice;
use Nodus\LexwareOfficeApi\Data\Enums\LeadingPrice;

class ArticlePriceFactory extends Factory
{
    public static function make(): ArticlePrice
    {
        $faker = \Faker\Factory::create();

        $data = [
            'leadingPrice' => $faker->randomElement(LeadingPrice::cases()),
            'taxRate' => $faker->randomElement([7, 19]),
        ];
        $data[($data['leadingPrice'] == LeadingPrice::NET) ? 'netPrice' : 'grossPrice'] = $faker->randomFloat(2, 0, 100);

        return ArticlePrice::from($data);
    }
}
