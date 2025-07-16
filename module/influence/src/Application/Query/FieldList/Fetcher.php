<?php

declare(strict_types=1);

namespace Influence\Application\Query\FieldList;

use Influence\Domain\Entity\Field;
use Influence\Domain\Repository\FieldRepositoryInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private FieldRepositoryInterface $fieldRepository
    ) {
    }

    /**
     * @param Query $query
     * @return FieldDto[]
     */
    public function __invoke(Query $query): array
    {
        $fields = $this->fieldRepository->findByCriteria([
            "tableId" => $query->tableId
        ]);

        return array_map(fn (Field $field) => FieldDto::getDto($field), $fields);
    }
}
