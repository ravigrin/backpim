<?php
declare(strict_types=1);

namespace Mobzio\EndPoint\Command;

use Mobzio\Application\Command\Import\Link\Command as LinksWithShortStatImportCommand;
use Mobzio\Application\Command\Import\Stat\Command as FullStatImportCommand;
use Shared\Domain\Command\CommandBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'mobzio:import',
    description: 'Загрузка ссылок и статистики с Mobzio',
)] final class ImportCommand extends Command
{
    private const string DEFAULT_SOURCE = 'dwh';

    public function __construct(
        private readonly CommandBusInterface $commandBus,
        string                               $name = null
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->addArgument(
            'target',
            InputArgument::REQUIRED,
            'Target import (link / stat)'
        );

        $this->addArgument(
            'source',
            InputArgument::OPTIONAL,
            'Import source (dwh / api)'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $source = $input->getArgument('source') ?? self::DEFAULT_SOURCE;
        match ($input->getArgument('target')) {
            'link' => $this->commandBus->dispatch(new LinksWithShortStatImportCommand($source)),
            'stat' => $this->commandBus->dispatch(new FullStatImportCommand($source)),
            default => 'target not exits'
        };

        return Command::SUCCESS;
    }
}

