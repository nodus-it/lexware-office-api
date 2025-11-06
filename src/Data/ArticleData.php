<?php

namespace Nodus\LexwareOfficeApi\Data;

use Nodus\LexwareOfficeApi\Data\Enums\ArticleType;
use Nodus\LexwareOfficeApi\Data\Traits\HasCreateAndUpdatedDate;
use Nodus\LexwareOfficeApi\Data\Traits\HasTimestamps;
use Nodus\LexwareOfficeApi\Data\Traits\HasVersioning;

class ArticleData extends BaseData
{
    use HasCreateAndUpdatedDate;
    use HasTimestamps;
    use HasVersioning;

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

    // Version is handled by HasVersioning trait
}
