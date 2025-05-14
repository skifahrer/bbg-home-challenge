<?php

declare(strict_types=1);

namespace App\Filter;

class ProductCategoryFilter implements FilterInterface
{
    public const int DEFAULT_PAGE = 1;
    public const int DEFAULT_PAGE_SIZE = 10;
    public const int MAX_PAGE_SIZE = 1000;

    public function __construct(
        private int $page = self::DEFAULT_PAGE,
        private int $limit = self::DEFAULT_PAGE_SIZE,
        private string $nameSearch = '',
        private string $name = '',
    ) {
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

    public function getNameSearch(): string
    {
        return $this->nameSearch;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function setLimit(int $limit): void
    {
        $this->limit =  ($limit > self::MAX_PAGE_SIZE) ? self::MAX_PAGE_SIZE : $limit;
    }

    public function setNameSearch(string $nameSearch): void
    {
        $this->nameSearch = $nameSearch;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}