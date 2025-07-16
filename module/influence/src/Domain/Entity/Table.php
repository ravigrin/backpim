<?php

declare(strict_types=1);

namespace Influence\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;

/**
 *
 */
#[ORM\Entity]
#[ORM\Table('influence_table')]
#[ORM\HasLifecycleCallbacks]
class Table extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(unique: true)]
    private int    $tableId;

    public function __construct(
        /**
         * Название таблицы
         */
        #[ORM\Column(length: 1000)]
        private string $title,
        /**
         *
         */
        #[ORM\Column(length: 1000)]
        private string $alias,
        /**
         * Колличество строк в таблице
         */
        #[ORM\Column]
        private int    $rowCount = 0,
        /**
         *
         */
        #[ORM\Column]
        private int    $customOrder = 1,
        /**
         * Статус для удаления
         */
        #[ORM\Column]
        private bool   $isDeleted = false,
    ) {
    }

    public function setTableId(int $tableId): void
    {
        $this->tableId = $tableId;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    public function setCustomOrder(int $customOrder): void
    {
        $this->customOrder = $customOrder;
    }

    public function setIsDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function setRowCount(int $rowCount): void
    {
        $this->rowCount = $rowCount;
    }

    public function getTableId(): int
    {
        return $this->tableId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getCustomOrder(): int
    {
        return $this->customOrder;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }


}
