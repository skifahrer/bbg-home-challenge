<?php

declare(strict_types=1);

namespace App\Controller\api;

use App\Factory\ProductCategoryFilterFactory;
use App\Factory\ProductFilterFactory;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

readonly class ProductApiController
{
    public function __construct(
        private ProductService $productService,
    ) {
    }

    public function getList(Request $request): JsonResponse
    {
        $filter = ProductFilterFactory::createFromRequest($request);
        $dataList = $this->productService->getProductList($filter);
        return new JsonResponse($dataList->toArray());
    }

    public function getDetail(Request $request, int $id): JsonResponse
    {
        $product = $this->productService->getProductDetailById($id);
        return new JsonResponse($product->toArray());
    }

    public function getProductCategoryList(Request $request): JsonResponse
    {
        $filter = ProductCategoryFilterFactory::createFromRequest($request);
        $dataList = $this->productService->getProductCategoryList($filter);
        return new JsonResponse($dataList->toArray());
    }
}
