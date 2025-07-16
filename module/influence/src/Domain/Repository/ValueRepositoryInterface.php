<?php

namespace Influence\Domain\Repository;

use Influence\Domain\Entity\Value;

interface ValueRepositoryInterface
{
    /**
     * @param int $tableId
     * @param int $rowId
     * @return Value[]
     */
    public function findByTableAndAlias(int $tableId, int $rowId): array;

    public function findValueBy(int $tableId): array;
}
