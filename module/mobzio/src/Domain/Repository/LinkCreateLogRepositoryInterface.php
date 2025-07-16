<?php

namespace Mobzio\Domain\Repository;

use Mobzio\Domain\Entity\LinkCreateLog;

interface LinkCreateLogRepositoryInterface
{
    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return LinkCreateLog[]
     */
    public function findBy(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array;

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneBy(
        array  $criteria,
        ?array $orderBy = null,
    ): ?LinkCreateLog;
}