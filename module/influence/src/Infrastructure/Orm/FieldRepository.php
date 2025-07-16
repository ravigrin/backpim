<?php

namespace Influence\Infrastructure\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Influence\Domain\Entity\Field;
use Influence\Domain\Repository\FieldRepositoryInterface;

readonly class FieldRepository implements FieldRepositoryInterface
{
    /** @psalm-var EntityRepository<Field> */
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(Field::class);
    }

    public function findOneByCriteria(array $criteria, ?array $orderBy = null,): ?Field
    {
        return $this->repository->findOneBy($criteria, $orderBy);
    }

    public function findByCriteria(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }
}
