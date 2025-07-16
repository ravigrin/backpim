<?php

declare(strict_types=1);

namespace Mobzio\Application\Query\Link\GetLastOne;

use Shared\Domain\Query\QueryInterface;

final class Query implements QueryInterface
{
    public function __construct(
        public string $hash
    )
    {
    }
}
