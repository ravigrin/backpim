<?php

namespace Influence\Application\Query\TableList;

use Influence\Domain\Entity\Table;

class TableDto
{
    public int $id;

    public string $name;

    public static function getDto(Table $table): self
    {
        $dto = new self();
        $dto->id = $table->getTableId();
        $dto->name = $table->getTitle();

        return $dto;
    }
}
