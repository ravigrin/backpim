<?php
declare(strict_types=1);

namespace Mobzio\Application\Query\Link\GetAllExcel;

use Mobzio\Domain\Service\ExcelService;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ExcelService      $excelService
    )
    {
    }

    public function __invoke(Query $query): false|string
    {
        return $this->excelService->getLinkAll();
    }
}
