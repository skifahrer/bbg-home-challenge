<?php

namespace App\Repository;

use App\Filter\FilterInterface;

interface RepositoryInterface
{
    public function findById(int $id): array;

    public function findByFilter(FilterInterface $filter): array;

    public function findTotalCountByFilter(FilterInterface $filter): int;
}