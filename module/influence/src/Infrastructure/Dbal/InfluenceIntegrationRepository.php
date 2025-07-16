<?php

namespace Influence\Infrastructure\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Influence\Domain\Repository\InfluenceIntegrationRepositoryInterface;

readonly class InfluenceIntegrationRepository implements InfluenceIntegrationRepositoryInterface
{
    private Connection $connection;

    public function __construct(
        private ManagerRegistry $doctrine,
    ) {
        /** @var Connection $dwh */
        $dwh              = $this->doctrine->getConnection('dwh');
        $this->connection = $dwh;
    }

    /**
     * @param mixed[] $documents
     * @throws Exception
     */
    public function save(array $documents): void
    {
        $json = json_encode($documents, JSON_UNESCAPED_UNICODE);
        //может вернуться формула с кавычкой - выпиливаем
        $json = str_replace("'", "", $json);
        $sql = sprintf("exec php_bot.blogger_data_import_v2 @Data = '%s'", $json);
        $this->connection->executeQuery($sql);
    }
}
