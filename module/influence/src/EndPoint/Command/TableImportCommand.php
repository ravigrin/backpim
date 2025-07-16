<?php

declare(strict_types=1);

namespace Influence\EndPoint\Command;

use Influence\Domain\Entity\Field;
use Influence\Domain\Entity\Table;
use Influence\Domain\Repository\MicrosoftIntegrationInterface;
use Influence\Domain\Repository\TableRepositoryInterface;
use Shared\Domain\Service\EntityStoreService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'influence:import:table',
    description: 'Обновление таблиц с блогерами',
)] final class TableImportCommand extends Command
{
    public function __construct(
        private TableRepositoryInterface      $tableRepository,
        private MicrosoftIntegrationInterface $microsoftIntegration,
        private readonly EntityStoreService   $entityStoreService,
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
            "INFLUENCE stories",
            "INFLUENCE reels 2 Mio",
            "Influence БАРТЕР new"
        ];

        foreach ($tables as $key => $tableName) {
            $table = $this->tableRepository->findOneByCriteria(["title" => $tableName]);
            if (is_null($table)) {
                $table = new Table(
                    title: $tableName,
                    alias: $tableName,
                    rowCount: 0,
                    customOrder: $key
                );
                $this->entityStoreService->commit($table);
            }
        }

        return Command::SUCCESS;
    }
}
