<?php

namespace Mobzio\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Mobzio\Application\Query\Statistic\Dto\FullStatisticDto;
use Mobzio\Domain\Entity\FullStatistic;
use Mobzio\Domain\Repository\FullStatisticRepositoryInterface;

class FullStatisticRepository implements FullStatisticRepositoryInterface
{
    /** @psalm-var EntityRepository<FullStatistic> */
    private EntityRepository $repository;

    public function __construct(
        private readonly Connection             $connection,
        private readonly EntityManagerInterface $entityManager,
    )
    {
        $this->repository = $this->entityManager->getRepository(FullStatistic::class);
    }

    public function findBy(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(
        array  $criteria,
        ?array $orderBy = null,
    ): ?FullStatistic
    {
        return $this->repository->findOneBy($criteria, $orderBy);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getRowsCount(?string $dateFrom = null, ?string $dateTo = null, ?string $linkId = null): int
    {
        if ($linkId) {
            $sql = "SELECT count(MS.full_statistic_id) FROM mobzio_full_statistic MS 
                WHERE MS.link_id = '%s' AND MS.add_time BETWEEN '%s' AND '%s'";
            $query = sprintf($sql, $linkId, strtotime($dateFrom), strtotime($dateTo));
        } else {
            $sql = "SELECT count(MS.full_statistic_id) FROM mobzio_full_statistic MS 
                WHERE MS.add_time BETWEEN '%s' AND '%s'";
            $query = sprintf($sql, strtotime($dateFrom), strtotime($dateTo));
        }

        return (int)$this->connection->executeQuery($query)->fetchOne();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getByLink(
        string $linkId, ?string $dateFrom = null, ?string $dateTo = null, ?int $limit = null, ?int $offset = null
    ): ?array
    {
        if ($dateTo && $dateFrom) {
            if ($limit && $offset) {
                $sql = "SELECT MS.user_agent, MS.add_time, MS.is_mobile FROM mobzio_full_statistic MS 
                        WHERE MS.link_id = '%s' AND MS.add_time BETWEEN '%s' AND '%s'
                        ORDER BY MS.add_time
                        OFFSET %s ROWS
                        FETCH NEXT %s ROWS ONLY";
                $query = sprintf($sql, $linkId, strtotime($dateFrom), strtotime("+1 day", strtotime($dateTo)), $offset, $limit);
            } else {
                $sql = "SELECT MS.user_agent, MS.add_time, MS.is_mobile FROM mobzio_full_statistic MS 
                        WHERE MS.link_id = '%s' AND MS.add_time BETWEEN '%s' AND '%s'
                        ORDER BY MS.add_time";
                $query = sprintf($sql, $linkId, strtotime($dateFrom), strtotime("+1 day", strtotime($dateTo)));
            }
        } else {
            if ($limit && $offset) {
                $sql = "SELECT MS.user_agent, MS.add_time, MS.is_mobile FROM mobzio_full_statistic MS 
                        WHERE MS.link_id = '%s'
                        ORDER BY MS.add_time
                        OFFSET %s ROWS
                        FETCH NEXT %s ROWS ONLY";
                $query = sprintf($sql, $linkId, $offset, $limit);
            } else {
                $sql = "SELECT MS.user_agent, MS.add_time, MS.is_mobile FROM mobzio_full_statistic MS 
                        WHERE MS.link_id = '%s'
                        ORDER BY MS.add_time";
                $query = sprintf($sql, $linkId);
            }
        }

        $statistics = $this->connection->executeQuery($query)->fetchAllAssociative();

        return array_map(fn(array $statistic) => new FullStatisticDto(
            userAgent: $statistic['user_agent'],
            addTime: date('d.m.Y H:i:s', (int)$statistic['add_time']),
            isMobile: $statistic['is_mobile']
        ), $statistics);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getByPeriod(string $dateFrom, string $dateTo, int $limit, int $offset): ?array
    {
        $sql = "SELECT * FROM mobzio_full_statistic MS 
                WHERE MS.add_time BETWEEN '%s' AND '%s' 
                ORDER BY MS.add_time
                OFFSET %s ROWS
                FETCH NEXT %s ROWS ONLY";
        $query = sprintf($sql, strtotime($dateFrom), strtotime($dateTo), $offset, $limit);
        $statistics = $this->connection->executeQuery($query)->fetchAllAssociative();

        return array_map(fn(array $statistic) => new FullStatisticDto(
            userAgent: $statistic['user_agent'],
            addTime: date('d.m.Y H:i:s', (int)$statistic['add_time']),
            isMobile: $statistic['is_mobile']
        ), $statistics);
    }
}
