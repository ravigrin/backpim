<?php

declare(strict_types=1);

namespace Influence\EndPoint\Command;

use Influence\Application\Command\SelfPurchaseUpdate\Command as SelfPurchaseUpdateCommand;
use Shared\Domain\Command\CommandBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'influence:self-purchase:update',
    description: 'Обновление самовыкупов',
)] final class SelfPurchaseCommand extends Command
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
        $this->commandBus->dispatch(new SelfPurchaseUpdateCommand());

        return Command::SUCCESS;
    }
}
