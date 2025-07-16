<?php

declare(strict_types=1);

namespace Influence\Application\Query\TableList;

use Influence\Domain\Entity\Table;
use Influence\Domain\Repository\TableRepositoryInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private TableRepositoryInterface $tableRepository
    ) {
    }

    /**
     * @param Query $query
     * @return TableDto[]
     */
    public function __invoke(Query $query): array
    {
        $tables = $this->tableRepository->findByCriteria([]);

        return array_map(fn (Table $table) => TableDto::getDto($table), $tables);
    }
}
