<?php

namespace Nodus\LexwareOfficeApi\Utils;

use Illuminate\Support\Facades\Cache;
use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\PaginationPlugin\Paginator;
use Saloon\RateLimitPlugin\Limit;
use Saloon\RateLimitPlugin\Stores\LaravelCacheStore;
use Saloon\RateLimitPlugin\Traits\HasRateLimits;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\Traits\Plugins\HasTimeout;

class LexwareOfficeConnector extends Connector implements HasPagination
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;
    use HasRateLimits;
    use HasTimeout;

    protected int $connectTimeout = 30;

    protected int $requestTimeout = 30;

    public function resolveBaseUrl(): string
    {
        return 'https://api.lexoffice.io/v1/';
    }

    protected function defaultAuth(): ?Authenticator
    {
        return new TokenAuthenticator(config('lexware-office.auth.token'));
    }

    protected function resolveLimits(): array
    {
        return [
            Limit::allow(2)->everySeconds(1)->sleep(),
        ];
    }

    protected function resolveRateLimitStore(): LaravelCacheStore
    {
        return new LaravelCacheStore(Cache::store(config('lexware-office.rate_limit.store')));
    }

    public function paginate(Request $request): Paginator
    {
        return new LexwarePaginator($this, $request);
    }
}
