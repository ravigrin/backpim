<?php

namespace Mobzio\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Mobzio\Domain\Entity\LinkCreateLog;
use Mobzio\Domain\Repository\LinkCreateLogRepositoryInterface;

class LinkCreateLogRepository implements LinkCreateLogRepositoryInterface
{
    /** @psalm-var EntityRepository<LinkCreateLog> */
    private EntityRepository $repository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {
        $this->repository = $this->entityManager->getRepository(LinkCreateLog::class);
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return LinkCreateLog|null
     */
    public function findOneBy(array $criteria, ?array $orderBy = null,): ?LinkCreateLog
    {
        return $this->repository->findOneBy($criteria, $orderBy);
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return LinkCreateLog[]
     */
    public function findBy(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }
}
