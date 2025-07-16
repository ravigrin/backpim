<?php
declare(strict_types=1);

namespace Mobzio\Application\Query\Statistic\GetOneExcel;

use Mobzio\Domain\Service\ExcelService;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ExcelService $excelService
    )
    {
    }

    public function __invoke(Query $query): false|string
    {
        return $this->excelService->getLinkStat(
            linkId: $query->linkId,
            dateFrom: $query->dateFrom,
            dateTo: $query->dateTo
        );
    }
}
