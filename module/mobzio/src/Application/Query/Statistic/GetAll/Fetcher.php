<?php
declare(strict_types=1);

namespace Mobzio\Application\Query\Statistic\GetAll;

use Mobzio\Application\Query\Statistic\Dto\StatisticDto;
use Mobzio\Domain\Repository\FullStatisticRepositoryInterface;
use Shared\Domain\Query\QueryHandlerInterface;
use Shared\Domain\Specification\QueryResponse\PaginationDto;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private FullStatisticRepositoryInterface $fullStatisticRepository
    )
    {
    }

    /**
     * @param Query $query
     * @return array{'stats': StatisticDto[], 'pagination': PaginationDto}
     */
    public function __invoke(Query $query): array
    {
        $offset = ($query->page > 1) ? $query->perPage * (--$query->page) : 0;

        if ($query->linkId) {
            $statistics = $this->fullStatisticRepository->getByLink(
                linkId: $query->linkId,
                dateFrom: $query->dateFrom,
                dateTo: $query->dateTo,
                limit: $query->perPage,
                offset: $offset
            );

            $rowsCount = $this->fullStatisticRepository->getRowsCount($query->dateFrom, $query->dateTo, $query->linkId);

        } elseif ($query->dateFrom && $query->dateTo) {
            $statistics = $this->fullStatisticRepository->getByPeriod(
                dateFrom: $query->dateFrom,
                dateTo: $query->dateTo,
                limit: $query->perPage,
                offset: $offset
            );

            $rowsCount = $this->fullStatisticRepository->getRowsCount($query->dateFrom, $query->dateTo);

        } else {
            $statistics = $this->fullStatisticRepository->findBy(
                criteria: [],
                orderBy: ['createdAt' => 'ASC'],
                limit: $query->perPage,
                offset: $offset
            );

            $rowsCount = $this->fullStatisticRepository->getRowsCount();
        }

        $pageCount = (int)ceil($rowsCount / $query->perPage);

        return [
            'stats' => $statistics,
            'pagination' => new PaginationDto(
                rowsCount: $rowsCount,
                pageCount: $pageCount,
                page: $query->page,
                perPage: $query->perPage
            )
        ];
    }
}
