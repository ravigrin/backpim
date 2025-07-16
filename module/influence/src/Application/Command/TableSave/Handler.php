<?php

declare(strict_types=1);

namespace Influence\Application\Command\TableSave;

use Influence\Domain\Entity\Value;
use Influence\Domain\Repository\FieldRepositoryInterface;
use Influence\Domain\Repository\TableRepositoryInterface;
use Influence\Domain\Repository\ValueRepositoryInterface;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private TableRepositoryInterface $tableRepository,
        private FieldRepositoryInterface $fieldRepository,
        private ValueRepositoryInterface $valueRepository,
        private PersistenceInterface     $persistenceRepository
    ) {
    }

    public function __invoke(Command $command): void
    {
        $newValues = $command->values;

        $table = $this->tableRepository->findOneByCriteria([
            "tableId" => $command->tableId
        ]);
        if (is_null($table)) {
            return;
        }

        $fields = $this->fieldRepository->findByCriteria([
            "alias" => array_keys($newValues)
        ]);

        $buildValues = [];
        foreach ($fields as $field) {
            $buildValues[$field->getFieldId()] = $newValues[$field->getAlias()];
        }

        if ($command->rowId === null) {
            // add values
            $maxRow = $table->getRowCount() + 1;
            foreach ($buildValues as $fieldId => $value) {
                $value = new Value(
                    tableId: $table->getTableId(),
                    fieldId: $fieldId,
                    row: $maxRow,
                    value: $value
                );
                $this->persistenceRepository->persist($value);
            }
            $table->setRowCount($maxRow);

        } else {
            // edit values
            $localValues = $this->valueRepository->findByTableAndAlias(
                tableId: $command->tableId,
                rowId: $command->rowId
            );

            foreach ($buildValues as $fieldId => $newValue) {
                foreach ($localValues as $localValue) {
                    if ($fieldId === $localValue->getFieldId()) {
                        $localValue->setValue($newValue);
                        $this->persistenceRepository->persist($localValue);
                    }
                }
            }
        }
        $this->persistenceRepository->flush();
    }

}
