<?php

namespace Mobzio\Domain\Repository;

use Mobzio\Domain\Entity\Statistic;

interface StatisticRepositoryInterface
{
    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return Statistic[]
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
    ): ?Statistic;

    /**
     * Возвращает общее кол-во краткой статистики (сегодня, вчера, всего)
     * @return int
     */
    public function getRowsCount(): int;
}
