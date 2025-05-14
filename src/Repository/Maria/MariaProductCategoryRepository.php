<?php
declare(strict_types=1);

namespace App\Repository\Maria;

use App\Core\Database\DatabaseConnectionInterface;
use App\Filter\FilterInterface;
use App\Filter\ProductCategoryFilter;
use App\Filter\ProductFilter;
use App\Repository\ProductCategoryRepositoryInterface;
use PDO;
use PDOStatement;

/**
 * @template-implements  FilterInterface<ProductCategoryFilter>
 */
class MariaProductCategoryRepository implements ProductCategoryRepositoryInterface
{
    private PDO $manager;
    public function __construct(
        private readonly DatabaseConnectionInterface $connection,
    ) {
        $this->manager = $this->connection->getManager();
    }

    public function findById(int $id): array
    {
        $stmt = $this->manager->prepare("
        SELECT c.* FROM category WHERE c.id = :id
        ");

        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return is_array($data) ? $data : [];
    }


    public function findByFilter(FilterInterface $filter): array
    {
        $sql = '
            SELECT c.*
            FROM category as c 
        ';

        $stmt = $this->executeFilter($sql, $filter);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findTotalCountByFilter(FilterInterface $filter): int
    {
        $sql = '
            SELECT
                COUNT(c.id) as total
            FROM category as c
        ';

        $stmt = $this->executeFilter($sql, $filter, false);
        $total = $stmt->fetchColumn();
        return (int) $total;
    }

    private function executeFilter(string $sql, FilterInterface $filter, bool $isLimitAllowed = true): PDOStatement
    {
        if ($isLimitAllowed) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $sql = $this->getFilterSql($filter, $sql);
        $stmt = $this->manager->prepare($sql);

        if ($isLimitAllowed) {
            $stmt->bindValue(':limit', $filter->getLimit(), PDO::PARAM_INT);
            $stmt->bindValue(':offset', $filter->getOffset(), PDO::PARAM_INT);
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
