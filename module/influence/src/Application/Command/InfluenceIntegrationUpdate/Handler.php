<?php

declare(strict_types=1);

namespace Influence\Application\Command\InfluenceIntegrationUpdate;

use Influence\Domain\Service\InfluenceIntegrationService;
use Shared\Domain\Command\CommandHandlerInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private InfluenceIntegrationService $influenceIntegrationService
    ) {
    }

    public function __invoke(Command $command): void
    {
        $this->influenceIntegrationService->updateDocument();
    }
}
