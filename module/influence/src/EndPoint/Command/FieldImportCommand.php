<?php

declare(strict_types=1);

namespace Influence\EndPoint\Command;

use Influence\Domain\Entity\Field;
use Influence\Domain\Entity\Value;
use Influence\Domain\Repository\MicrosoftIntegrationInterface;
use Influence\Domain\Repository\TableRepositoryInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Shared\Domain\Service\EntityStoreService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'influence:import:field',
    description: 'Обновление таблиц с блогерами',
)] final class FieldImportCommand extends Command
{
    public function __construct(
        private TableRepositoryInterface      $tableRepository,
        private MicrosoftIntegrationInterface $microsoftIntegration,
        private EntityStoreService            $entityStoreService,
        private PersistenceInterface          $persistenceRepository,
        string                                $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tables = [
            "INFLUENCE stories" => "A1:AT400",
            "INFLUENCE reels 2 Mio" => "A1:AK29",
            "Influence БАРТЕР new" => "A1:V500"
        ];

        foreach ($tables as $tableName => $range) {

            $table = $this->tableRepository->findOneByCriteria(["title" => $tableName]);

            try {
                $document = $this->microsoftIntegration->getData(
                    drive: 'b!l3AC4vy-cUal3blcNbhtXoR2-817TohMlslES_N46v0Vdt-3b0RrSLLY2AHZUN3h',
                    item: '01RNFONAKGUEPPSCCBRVCIZQZSADE6PJZH',
                    worksheet: $table->getTitle(),
                    range: $range
                );
            } catch (\Exception $exception) {
                echo sprintf("table: %s error: %s", $tableName, $exception->getMessage());
                continue;
            }

            $fields = array_shift($document);

            /** @var Field[] $fieldCollection */
            $fieldCollection = [];
            foreach ($fields as $key => $fieldName) {
                if (empty($fieldName)) {
                    continue;
                }

                $field = new Field(
                    tableId: $table->getTableId(),
                    title: $fieldName,
                    alias: (string)crc32($fieldName),
                );
                $this->entityStoreService->commit($field);
                $fieldCollection[$key] = $field;
            }

            foreach ($document as $row => $fieldData) {
                foreach ($fieldCollection as $fieldId => $field) {
                    $value = new Value(
                        tableId: $table->getTableId(),
                        fieldId: $fieldCollection[$fieldId]->getFieldId(),
                        row: $row,
                        value: $fieldData[$fieldId]
                    );
                    $this->persistenceRepository->persist($value);
                }
                $this->persistenceRepository->flush();
            }
            $table->setRowCount(count($document));
            $this->entityStoreService->commit($table);
        }
        return Command::SUCCESS;
    }
}
