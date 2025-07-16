<?php

declare(strict_types=1);

namespace Mobzio\Application\Query\Statistic\GetAll;

use Shared\Domain\Query\QueryInterface;

final class Query implements QueryInterface
{
    public function __construct(
        public ?string $linkId = null,
        public ?string $dateFrom = null,
        public ?string $dateTo = null,
        public ?int    $page = 1,
        public ?int    $perPage = 30
    )
    {
    }
}
