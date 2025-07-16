<?php
declare(strict_types=1);

namespace Mobzio\Application\Command\Import\Link;

use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    public function __construct(
        public string $source
    )
    {
    }
}
