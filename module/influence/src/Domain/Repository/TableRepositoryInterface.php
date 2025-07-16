<?php

namespace Influence\Domain\Repository;

use Influence\Domain\Entity\Table;

interface TableRepositoryInterface
{
    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return Table[]
     */
    public function findByCriteria(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array;

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Table;
}