<?php

declare(strict_types=1);

namespace App\Factory;

use App\Filter\ProductCategoryFilter;
use Symfony\Component\HttpFoundation\Request;

class ProductCategoryFilterFactory
{
    public static function createFromRequest(Request $request): ProductCategoryFilter
    {
        $searchString = $request->query->get('search') ?? '';
        $searchString = $request->query->get('name') ?? $searchString;

        $page = max(
            ProductCategoryFilter::DEFAULT_PAGE,
            (int)($request->query->get('page') ?? 0)
        );

        $limit = min(
            1000,
            (int)($request->query->get('limit') ?? ProductCategoryFilter::DEFAULT_PAGE_SIZE),
        );

        return new ProductCategoryFilter($page, $limit, $searchString);
    }
}