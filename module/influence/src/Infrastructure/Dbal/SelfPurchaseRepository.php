<?php

namespace Influence\Infrastructure\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Influence\Domain\Repository\SelfPurchaseRepositoryInterface;

readonly class SelfPurchaseRepository implements SelfPurchaseRepositoryInterface
{
    private Connection $connection;

    public function __construct(
        private ManagerRegistry $doctrine,
    ) {
        /** @var Connection $dwh */
        $dwh = $this->doctrine->getConnection('dwh');
        $this->connection = $dwh;
    }

    /**
     * @param mixed[] $documents
     * @throws Exception
     */
    public function save(array $documents): void
    {
        $json = json_encode($documents, JSON_UNESCAPED_UNICODE);
        $sql = sprintf("exec php_bot.selfpurchase_import @Data = '%s'", $json);
        $this->connection->executeQuery($sql);
    }
}
