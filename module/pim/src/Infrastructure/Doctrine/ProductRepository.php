<?php

declare(strict_types=1);

namespace Pim\Infrastructure\Doctrine;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Pim\Domain\Entity\Product;
use Pim\Domain\Repository\Pim\ProductInterface;

final readonly class ProductRepository implements ProductInterface
{
    /** @psalm-var EntityRepository<Product> */
    private EntityRepository $repository;

    private Connection $connection;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(Product::class);
        $this->connection = $this->entityManager->getConnection();
    }

    public function findByVendorCode(string $sku): ?Product
    {
        return $this->repository->findOneBy(
            [
                'vendorCode' => $sku,
                'isDeleted' => false
            ]
        );
    }

    public function getIncrementBy(string $brandId, ?string $productLineId, bool $isKit): int
    {
        $qb = $this->repository->createQueryBuilder('p')
            ->select('SUBSTRING(p.vendorCode, 9, 4) as vendorCode')
            ->where("p.brandId = :brand")
            ->setParameter('brand', $brandId, Types::STRING);

        if ($productLineId) {
            $qb->andWhere("p.productLineId = :productLine")
                ->setParameter('productLine', $productLineId, Types::STRING);
        }

        if ($isKit) {
            $qb->andWhere('p.isKit = true');
        } else {
            $qb->andWhere('p.isKit = false');
        }

        $qb->orderBy('vendorCode', 'desc');
        $qb->setMaxResults(1);

        /** @var array{vendorCode: string}|null $result */
        $result = $qb->getQuery()->getResult();

        $inc = 0;

        if (isset($result[0]['vendorCode'])) {
            $inc = (int)$result[0]['vendorCode'];
        }

        return $inc + 1;
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneByCriteria(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Product {
        return $this->repository->findOneBy($criteria, $orderBy);
    }

    /**
     * @return array{
     *     productId: string,
     *     vendorCode: string,
     *     productName: string|null,
     *     unitName: string,
     *     brandName: string,
     *     productLineName: string|null,
     *     productStatus: string|null
     * }[]
     * @throws Exception
     */
    public function findProductsForFront(): array
    {
        $sql = <<<SQL
            select pp.product_id                  as productId,
                   pp.vendor_code                 as vendorCode,
                   JSON_VALUE(ppa1.value, '$[0]') AS productName,
                   pu.name                        as unitName,
                   pn.name                        as brandName,
                   ppl.name                       as productLineName,
                   JSON_VALUE(ppa2.value, '$[0]') AS productStatus
            from pim_product pp
                     left join pim_unit pu on pp.unit_id = pu.unit_id
                     left join pim_brand pn on pp.brand_id = pn.brand_id
                     left join pim_product_line ppl on pp.product_line_id = ppl.product_line_id
                     left join pim_product_attribute ppa1 on pp.product_id = ppa1.product_id
                and ppa1.attribute_id = (SELECT pim_attribute.attribute_id FROM pim_attribute WHERE alias = 'name')
                     left join pim_product_attribute ppa2 on pp.product_id = ppa2.product_id
                and ppa2.attribute_id = (SELECT pim_attribute.attribute_id FROM pim_attribute WHERE alias = 'status')
        SQL;

        return $this->connection->executeQuery($sql)->fetchAllAssociative();
    }

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return Product[]
     */
    public function findByCriteria(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array {
        return $this->repository->findBy(
            $criteria,
            $orderBy,
            $limit,
            $offset,
        );
    }

    /**
     * @param string[] $productsId
     * @return string[]
     * @throws Exception
     */
    public function findAll(array $productsId): array
    {
        $sql = <<<SQL
            select product_id from pim_product
        SQL;

        $result = $this->connection->executeQuery(
            sql: $sql,
        )->fetchAllAssociative();

        /** @var string[] $result */
        $result = array_column($result, 'product_id');

        return $result;
    }
}
