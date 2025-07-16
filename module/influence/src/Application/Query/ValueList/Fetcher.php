<?php

declare(strict_types=1);

namespace Influence\Application\Query\ValueList;

use Influence\Domain\Repository\ValueRepositoryInterface;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ValueRepositoryInterface $valueRepository
    ) {
    }

    public function __invoke(Query $query): array
    {
        return $this->valueRepository->findValueBy($query->tableId);
    }
}
