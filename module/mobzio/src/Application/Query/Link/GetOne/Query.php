<?php

declare(strict_types=1);

namespace Mobzio\Application\Query\Link\GetOne;

use Shared\Domain\Query\QueryInterface;

final class Query implements QueryInterface
{
    public function __construct(
        public string  $linkId,
        public ?string $priceDate = null
    )
    {
    }
}
