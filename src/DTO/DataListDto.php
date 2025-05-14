<?php

declare(strict_types=1);

namespace App\DTO;

class DataListDto implements Dto
{
    public const int DEFAULT_PAGE = 1;
    public const int DEFAULT_PAGE_LIMIT = 10;
    public const int DEFAULT_TOTAL_COUNT = 0;

    public function __construct(
        public array $data,
        public int $currentPage,
        public int $pageSize,
        public int $total,
    ) {
    }

    public static function createFromArray(array $data): self
    {
        return new self (
            $data['data'] ?? [],
            (int)$data['currentPage'] ?? self::DEFAULT_PAGE,
            (int)$data['pageLimit'] ?? self::DEFAULT_PAGE_LIMIT,
            (int)$data['total'] ?? self::DEFAULT_TOTAL_COUNT,
        );
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'currentPage' => $this->currentPage,
            'pageSize' => $this->pageSize,
            'dataSize' => count($this->data),
            'totalDataCount' => $this->total,
            'totalPageCount' => (int)ceil($this->total / $this->pageSize),
        ];
    }
}
