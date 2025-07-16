<?php

declare(strict_types=1);

namespace Influence\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;

/**
 *
 */
#[ORM\Entity]
#[ORM\Table('influence_value')]
#[ORM\HasLifecycleCallbacks]
class Value extends AggregateRoot
{
    /**
     *
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(unique: true)]
    private int    $valueId;

    public function __construct(
        /**
         *
         */
        #[ORM\Column]
        private int    $tableId,
        /**
         *
         */
        #[ORM\Column]
        private int    $fieldId,
        /**
         *
         */
        #[ORM\Column]
        private int    $row,
        /**
         *
         */
        #[ORM\Column(length: 2000)]
        private string $value,
    ) {
    }

    public function getRow(): int
    {
        return $this->row;
    }

    public function setRow(int $row): void
    {
        $this->row = $row;
    }

    public function setValueId(int $valueId): void
    {
        $this->valueId = $valueId;
    }

    public function setTableId(int $tableId): void
    {
        $this->tableId = $tableId;
    }

    public function setFieldId(int $fieldId): void
    {
        $this->fieldId = $fieldId;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getValueId(): int
    {
        return $this->valueId;
    }

    public function getTableId(): int
    {
        return $this->tableId;
    }

    public function getFieldId(): int
    {
        return $this->fieldId;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
