<?php
declare(strict_types=1);

namespace Mobzio\Application\Command\MakeLink;

use Shared\Domain\Command\CommandInterface;

final class Command implements CommandInterface
{
    public function __construct(
        public string  $productId,
        public string  $phrase,
        public string  $blogger,
        public string  $hash,
        public ?string $shortcode = null
    )
    {
    }
}
