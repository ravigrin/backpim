<?php

namespace Influence\Infrastructure\Orm;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Influence\Domain\Entity\Value;
use Influence\Domain\Repository\ValueRepositoryInterface;

readonly class ValueRepository implements ValueRepositoryInterface
{
    private Connection $connection;

    /** @psalm-var EntityRepository<Value> */
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ManagerRegistry        $doctrine,
    ) {
        /** @var Connection $pim */
        $pim = $this->doctrine->getConnection('pim');
        $this->connection = $pim;
        $this->repository = $this->entityManager->getRepository(Value::class);

    }

    /**
     * @return Value[]
     */
    public function findByTableAndAlias(int $tableId, int $rowId): array
    {
        return $this->repository->findBy(
            [
                "tableId" => $tableId,
                "row" => $rowId
            ]
        );
    }

    public function findValueBy(int $tableId): array
    {
        $sql = <<<SQL
            select 
                inff.title as title, inff.alias as alias, inff.formula as formula, inff.type,
                infv.row as row, infv.value
            from influence_field as inff 
            left join influence_value infv on inff.field_id = infv.field_id and inff.table_id = infv.table_id
            where inff.table_id = %d
        SQL;

        $fieldValues = $this->connection->executeQuery(sprintf($sql, $tableId))->fetchAllAssociative();

        $result = [];
        foreach ($fieldValues as $fieldValue) {
            $result[$fieldValue['row']]["rowId"] = $fieldValue['row'];
            $result[$fieldValue['row']][$fieldValue['alias']] = $fieldValue['value'];

        }
        return array_values($result);
    }
}
