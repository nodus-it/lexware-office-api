<?php

namespace Nodus\LexwareOfficeApi\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class ArticleData extends BaseData
{
        public string $id;
        public ?string $title;
        public ?string $description;
        public ?string $type;
        public ?string $articleNumber;
        public ?string $gtin;
        public ?string $note;
        public ?string $unitName;
        public ?array $price;
        public ?int $version;
}
