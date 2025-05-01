<?php

namespace Nodus\LexwareOfficeApi\Data;

use Nodus\LexwareOfficeApi\Data\Enums\LeadingPrice;

class ArticlePrice extends BaseData
{
    public ?string $netPrice;

    public ?string $grossPrice;

    public LeadingPrice $leadingPrice;

    public ?string $taxRate;
}
