<?php

namespace Influence\Infrastructure\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Influence\Domain\Entity\Table;
use Influence\Domain\Repository\TableRepositoryInterface;

readonly class TableRepository implements TableRepositoryInterface
{
    /** @psalm-var EntityRepository<Table> */
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(Table::class);
    }

    public function findByCriteria(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Table {
        return $this->repository->findOneBy($criteria, $orderBy);
    }
}
