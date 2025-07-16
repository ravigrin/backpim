<?php
declare(strict_types=1);

namespace Mobzio\Application\Query\Link\GetAll;

use Mobzio\Application\Query\Link\Dto\LinkListDto;
use Mobzio\Domain\Repository\LinkRepositoryInterface;
use Mobzio\Domain\Service\StatisticService;
use Shared\Domain\Query\QueryHandlerInterface;
use Shared\Domain\Specification\QueryResponse\PaginationDto;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private LinkRepositoryInterface $linkRepository,
        private StatisticService        $statisticService

    )
    {
    }

    /**
     * @param Query $query
     * @return array{'links': LinkListDto[], 'pagination': PaginationDto}
     */
    public function __invoke(Query $query): array
    {
        $rowsCount = $this->linkRepository->getRowsCount();
        $pageCount = (int)ceil($rowsCount / $query->perPage);

        $offset = ($query->page > 1) ? $query->perPage * (--$query->page) : 0;

        $links = $this->linkRepository->findBy(
            criteria: [],
            orderBy: ['createdAt' => 'DESC'],
            limit: $query->perPage,
            offset: $offset
        );

        $linkListDto = [];
        foreach ($links as $link) {
            if (!$link->getProductId()) {
                continue;
            }

            $linkListDto[] = LinkListDto::getDto(
                link: $link,
                stats: $this->statisticService->getStatsWithSalesDto(
                    linkId: $link->getLinkId(),
                    productId: $link->getProductId()
                )
            );
        }

        return [
            'links' => $linkListDto,
            'pagination' => new PaginationDto(
                rowsCount: $rowsCount,
                pageCount: $pageCount,
                page: $query->page,
                perPage: $query->perPage
            )
        ];
    }
}
