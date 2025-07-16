<?php

declare(strict_types=1);

namespace Influence\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;

/**
 *
 */
#[ORM\Entity]
#[ORM\Table('influence_field')]
#[ORM\HasLifecycleCallbacks]
class Field extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(unique: true)]
    private int    $fieldId;

    public function __construct(
        /**
         *  id таблицы к которой относиться поле
         */
        #[ORM\Column]
        private int    $tableId,
        /**
         *  Название столбца
         */
        #[ORM\Column(length: 1000)]
        private string $title,
        /**
         *  Alias столбца
         */
        #[ORM\Column(length: 1000)]
        private string $alias,
        /**
         * Формула
         */
        #[ORM\Column(length: 1000)]
        private string $formula = "",
        /**
         * Тип поля
         */
        #[ORM\Column]
        private string $type = "string",
        /**
         * Статус для удаления
         */
        #[ORM\Column]
        private bool   $isDeleted = false,
    ) {
    }

    public function getFieldId(): int
    {
        return $this->fieldId;
    }

    public function setFieldId(int $fieldId): void
    {
        $this->fieldId = $fieldId;
    }

    public function getTableId(): int
    {
        return $this->tableId;
    }

    public function setTableId(int $tableId): void
    {
        $this->tableId = $tableId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    public function getFormula(): string
    {
        return $this->formula;
    }

    public function setFormula(string $formula): void
    {
        $this->formula = $formula;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }


}
