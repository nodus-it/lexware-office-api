<?php

namespace Nodus\LexwareOfficeApi\Data\Traits;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

trait HasCreateAndUpdatedDate
{
    #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s.vP')]
    public ?Carbon $createdDate;

    #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s.vP')]
    public ?Carbon $updatedDate;
}
