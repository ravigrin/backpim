<?php
declare(strict_types=1);

namespace Mobzio\Application\Query\Statistic\GetOneExcel;

use Shared\Domain\Query\QueryInterface;

final class Query implements QueryInterface
{
    public function __construct(
        public string  $linkId,
        public ?string $dateFrom = null,
        public ?string $dateTo = null
    )
    {
    }
}
