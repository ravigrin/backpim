<?php

namespace Mobzio\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Mobzio\Domain\Entity\Link;
use Mobzio\Domain\Repository\LinkRepositoryInterface;

class LinkRepository implements LinkRepositoryInterface
{
    /** @psalm-var EntityRepository<Link> */
    private EntityRepository $repository;

    public function __construct(
        private readonly Connection             $connection,
        private readonly EntityManagerInterface $entityManager,
    )
    {
        $this->repository = $this->entityManager->getRepository(Link::class);
    }

    public function findOneBy(array $criteria, ?array $orderBy = null,): ?Link
    {
        return $this->repository->findOneBy($criteria, $orderBy);
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

    /**
     * @return int
     * @throws Exception
     */
    public function getRowsCount(): int
    {
        $sql = "SELECT count(ML.link_id) FROM mobzio_link ML";
        return (int)$this->connection->executeQuery($sql)->fetchOne();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getLinkIdsWithVendorCode(): array
    {
        $sql = "SELECT ML.link_id, WP.vendor_code FROM mobzio_link ML
                LEFT JOIN wb_product WP ON ML.product_id = WP.product_id
        ";
        return $this->connection->executeQuery($sql)->fetchAllAssociative() ?? [];
    }
}
