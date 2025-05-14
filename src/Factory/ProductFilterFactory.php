<?php

declare(strict_types=1);

namespace App\Factory;

use App\Filter\ProductFilter;
use Symfony\Component\HttpFoundation\Request;

class ProductFilterFactory
{
    public static function createFromRequest(Request $request): ProductFilter
    {
        $categoryId = (int)$request->query->get('category') ?? null;

        $page = max(
            ProductFilter::DEFAULT_PAGE,
            (int)($request->query->get('page') ?? ProductFilter::DEFAULT_PAGE)
        );

        $limit = min(
            ProductFilter::DEFAULT_PAGE_SIZE,
            max(1, (int)($request->query->get('limit') ?? 10))
        );

        return new ProductFilter($page, $limit, $categoryId);
    }

    private static function setCategoryList(Request $request): array
    {
        $categoryList = [];
        $categoryListRaw = $request->query->get('category');
        if (is_string($categoryListRaw)) {
            $categoryList = array_filter(
                array_map('trim', explode(',', $categoryListRaw))
            );
        }

        return $categoryList;
    }
}