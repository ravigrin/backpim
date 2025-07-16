<?php

namespace Mobzio\Domain\Repository;

use Mobzio\Application\Query\Statistic\Dto\FullStatisticDto;
use Mobzio\Domain\Entity\FullStatistic;

interface FullStatisticRepositoryInterface
{
    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return FullStatistic[]
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
    ): ?FullStatistic;

    /**
     * Возвращает общее кол-во статистики по ссылке и/или датам
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $linkId
     * @return int
     */
    public function getRowsCount(?string $dateFrom = null, ?string $dateTo = null, ?string $linkId = null): int;

    /**
     * @param string $linkId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $limit
     * @param int|null $offset
     * @return FullStatisticDto[]|null
     */
    public function getByLink(
        string $linkId, ?string $dateFrom = null, ?string $dateTo = null, ?int $limit = null, ?int $offset = null
    ): ?array;

    /**
     * @param string $dateFrom
     * @param string $dateTo
     * @param int $limit
     * @param int $offset
     * @return FullStatisticDto[]|null
     */
    public function getByPeriod(string $dateFrom, string $dateTo, int $limit, int $offset): ?array;
}
