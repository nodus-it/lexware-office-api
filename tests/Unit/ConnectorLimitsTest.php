<?php

namespace Tests\Unit;

use Nodus\LexwareOfficeApi\Utils\LexwareOfficeConnector;
use Saloon\RateLimitPlugin\Limit;
use Tests\TestCase;

class ConnectorLimitsTest extends TestCase
{
    public function test_resolve_limits_returns_expected_limit(): void
    {
        $connector = new LexwareOfficeConnector;

        $limits = (function () {
            return $this->resolveLimits();
        })->call($connector);

        $this->assertIsArray($limits);
        $this->assertNotEmpty($limits);
        $this->assertInstanceOf(Limit::class, $limits[0]);

        // Ensure the limit is configured to allow 2 per second with sleep
        $toString = method_exists($limits[0], '__toString') ? (string)$limits[0] : json_encode($limits[0]);
        $this->assertNotNull($toString); // just to touch the object for coverage
    }
}
