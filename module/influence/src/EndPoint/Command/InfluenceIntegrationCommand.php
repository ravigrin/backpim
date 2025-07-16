<?php

declare(strict_types=1);

namespace Influence\EndPoint\Command;

use Influence\Application\Command\InfluenceIntegrationUpdate\Command as InfluenceIntegrationUpdateCommand;
use Shared\Domain\Command\CommandBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'influence:influence-integration:update',
    description: 'Обновление интеграций с блогерами',
)] final class InfluenceIntegrationCommand extends Command
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->commandBus->dispatch(new InfluenceIntegrationUpdateCommand());

        return Command::SUCCESS;
    }
}
