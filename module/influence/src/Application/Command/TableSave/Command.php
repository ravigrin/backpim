<?php

declare(strict_types=1);

namespace Influence\Application\Command\TableSave;

use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    /**
     * @param array<string, string> $values
     */
    public function __construct(
        public int   $tableId,
        public ?int   $rowId,
        public array $values,
    ) {
    }
}
