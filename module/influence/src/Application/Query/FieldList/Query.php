<?php

declare(strict_types=1);

namespace Influence\Application\Query\FieldList;

use Shared\Domain\Query\QueryInterface;

final class Query implements QueryInterface
{
    public function __construct(public int $tableId)
    {
    }
}
