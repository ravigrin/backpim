<?php
declare(strict_types=1);

namespace Mobzio\Application\Query\Link\GetAll;

use Shared\Domain\Query\QueryInterface;

final class Query implements QueryInterface
{
    public function __construct(
        public ?int $page = 1,
        public ?int $perPage = 30
    )
    {
    }
}
