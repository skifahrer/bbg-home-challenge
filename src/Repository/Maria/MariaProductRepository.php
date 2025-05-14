<?php

declare(strict_types=1);

namespace App\Repository\Maria;

use App\Core\Database\DatabaseConnectionInterface;
use App\Filter\FilterInterface;
use App\Filter\ProductFilter;
use App\Repository\ProductRepositoryInterface;
use PDO;
use PDOStatement;

/**
 * @template-implements FilterInterface<ProductFilter>
 */
class MariaProductRepository implements ProductRepositoryInterface
{
    private PDO $manager;

    public function __construct(
        private readonly DatabaseConnectionInterface $connection,
    ) {
        $this->manager = $this->connection->getManager();
    }

    public function findById(int $id): array
    {
        $sql = '
            SELECT
                p.*,
                c.id AS id_category,
                c.name as category_name,
                c.slug as category_slug
            FROM product as p
            LEFT JOIN category as c on c.id = p.category_id 
            WHERE p.id = :id
        ';
        $stmt = $this->manager->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return is_array($data) ? $data : [];
    }


    public function findByFilter(FilterInterface $filter): array
    {
        $sql = '
            SELECT
                p.*,
                c.id AS id_category,
                c.name as category_name,
                c.slug as category_slug
            FROM product as p
            LEFT JOIN category as c on c.id = p.category_id
        ';
        $stmt = $this->executeFilter($sql, $filter);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findTotalCountByFilter(FilterInterface $filter): int
    {
        $sql = '
            SELECT
                COUNT(p.id) as total
            FROM product as p
            LEFT JOIN category as c on c.id = p.category_id
        ';
        $stmt = $this->executeFilter($sql, $filter, false);
        $total = $stmt->fetchColumn();

        return (int) $total;
    }

    private function executeFilter(string $sql, FilterInterface $filter, bool $isLimitAllowed = true): PDOStatement
    {
        $params = [];

        if ($filter instanceof ProductFilter && $filter->getCategoryId()) {
            $sql .= " WHERE p.category_id = :category_id";
            $params["category_id"] = $filter->getCategoryId();
        }

        if ($isLimitAllowed) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $sql = $this->getFilterSql($filter, $sql);
        $stmt = $this->manager->prepare($sql);

        if ($isLimitAllowed) {
            $stmt->bindValue(':limit', $filter->getLimit(), PDO::PARAM_INT);
            $stmt->bindValue(':offset', $filter->getOffset(), PDO::PARAM_INT);
        }

        if (isset($params['category_id'])) {
            $stmt->bindValue(':category_id', $params['category_id'], PDO::PARAM_INT);
        }
        $stmt->execute();

        return $stmt;
    }

    private function getFilterParams(FilterInterface $filter): array
    {
        return [];
    }

    private function getFilterSql(FilterInterface $filter, string $sql = ''): string
    {
        return $sql;
    }
}