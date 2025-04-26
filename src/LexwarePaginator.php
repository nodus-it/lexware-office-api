<?php

namespace MeinVendor\MeinPackage;

use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Paginator;

class LexwarePaginator extends Paginator
{
    protected function applyPagination(Request $request): Request
    {
        $request->query()->add('page', $this->getCurrentPage());
        //$request->query()->add('size', "50"); // 25-250 erlaubt

        return $request;
    }

    protected function isLastPage(Response $response): bool
    {
        return $response->json('last');
    }

    protected function getPageItems(Response $response, Request $request): array
    {
        return $response->dto();
    }
}
