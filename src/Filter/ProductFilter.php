<?php
declare(strict_types=1);

namespace App\Filter;

use Symfony\Component\HttpFoundation\Request;

class ProductFilter implements FilterInterface
{
    public const int DEFAULT_PAGE = 1;
    public const int DEFAULT_PAGE_SIZE = 10;
    public const int MAX_PAGE_SIZE = 1000;

    public function __construct(
        private int $page,
        private int $limit,
        private ?int $categoryId,
    ) {
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return ($this->page - 1) * $this->limit;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function setLimit(int $limit): void
    {
        $this->limit =  ($limit > self::MAX_PAGE_SIZE) ? self::MAX_PAGE_SIZE : $limit;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }
}
