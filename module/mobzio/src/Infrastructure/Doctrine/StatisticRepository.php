<?php

namespace Mobzio\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Mobzio\Domain\Entity\Statistic;
use Mobzio\Domain\Repository\StatisticRepositoryInterface;

class StatisticRepository implements StatisticRepositoryInterface
{
    /** @psalm-var EntityRepository<Statistic> */
    private EntityRepository $repository;

    public function __construct(
        private readonly Connection             $connection,
        private readonly EntityManagerInterface $entityManager,
    )
    {
        $this->repository = $this->entityManager->getRepository(Statistic::class);
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
    ): ?Statistic
    {
        return $this->repository->findOneBy($criteria, $orderBy);
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getRowsCount(): int
    {
        $sql = "SELECT count(MS.statistic_id) FROM mobzio_statistic MS";
        return (int)$this->connection->executeQuery($sql)->fetchOne();
    }
}
