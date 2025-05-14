<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\DataListDto;
use App\DTO\Product\ProductDetailDto;
use App\Exceptions\ProductNotFoundExceptionAbstract;
use App\Filter\ProductCategoryFilter;
use App\Filter\ProductFilter;
use App\Repository\RepositoryInterface;

readonly class ProductService
{
    public function __construct(
        private RepositoryInterface $productRepository,
        private RepositoryInterface $productCategoryRepository,
    ) {
    }

    public function getProductDetailById(int $id): ProductDetailDto
    {
        $data = $this->productRepository->findById($id);
        if (count($data) === 0) {
            throw new ProductNotFoundExceptionAbstract();
        }

        return ProductDetailDto::createFromArray($data);
    }

    public function getProductList(ProductFilter $filter): DataListDto
    {
        $list = $this->productRepository->findByFilter($filter);
        $data = array_map(function ($product) {
            return ProductDetailDto::createFromArray($product)->toArray();
        }, $list);

        $totalCount = $this->productRepository->findTotalCountByFilter($filter);

        return new DataListDto($data, $filter->getPage(), $filter->getLimit(), $totalCount);
    }

    public function getProductCategoryList(ProductCategoryFilter $filter): DataListDto
    {
        $data = $this->productCategoryRepository->findByFilter($filter);
        $totalCount = $this->productCategoryRepository->findTotalCountByFilter($filter);

        return new DataListDto($data, $filter->getPage(), $filter->getLimit(), $totalCount);
    }
}