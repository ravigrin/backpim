<?php

declare(strict_types=1);

namespace Influence\Application\Command\SelfPurchaseUpdate;

use Influence\Domain\Service\SelfPurchaseService;
use Shared\Domain\Command\CommandHandlerInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private SelfPurchaseService $selfPurchaseService
    ) {
    }

    public function __invoke(Command $command): void
    {
        $this->selfPurchaseService->updateDocument();
    }
}
