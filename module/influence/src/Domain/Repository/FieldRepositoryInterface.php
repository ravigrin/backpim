<?php

namespace Influence\Domain\Repository;

use Influence\Domain\Entity\Field;

interface FieldRepositoryInterface
{
    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Field;

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return Field[]
     */
    public function findByCriteria(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array;
}