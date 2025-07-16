<?php

namespace Influence\Application\Query\FieldList;

use Influence\Domain\Entity\Field;

class FieldDto
{
    public string $title;

    public string $key;

    public string $formula;

    public string $pimType;

    public static function getDto(Field $field): self
    {
        $dto = new self();
        $dto->title = $field->getTitle();
        $dto->key = $field->getAlias();
        $dto->formula = $field->getFormula();
        $dto->pimType = $field->getType();

        return $dto;
    }
}
