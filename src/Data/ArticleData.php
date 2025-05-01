<?php

namespace Nodus\LexwareOfficeApi\Data;

use Nodus\LexwareOfficeApi\Data\Enums\ArticleType;
use Nodus\LexwareOfficeApi\Data\Traits\HasCreateAndUpdatedDate;

class ArticleData extends BaseData
{
    use HasCreateAndUpdatedDate;

    public string $id;

    public ?string $organizationId;

    public ?string $title;

    public ?string $description;

    public ?ArticleType $type;

    public ?string $articleNumber;

    public ?string $gtin;

    public ?string $note;

    public ?string $unitName;

    public ?ArticlePrice $price;

    public ?int $version;
}
