<?php

declare(strict_types=1);

namespace Mobzio\Application\Query\Link\GetOne;

use Mobzio\Application\Query\Link\Dto\LinkDto;
use Mobzio\Domain\Repository\LinkRepositoryInterface;
use Mobzio\Domain\Service\StatisticService;
use Shared\Domain\Query\QueryHandlerInterface;

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
     * @return LinkDto|null
     */
    public function __invoke(Query $query): LinkDto|null
    {
        if ($link = $this->linkRepository->findOneBy(['linkId' => $query->linkId])) {
            return LinkDto::getDto(
                link: $link,
                stat: $this->statisticService->getStatsWithSalesDto(
                    linkId: $link->getLinkId(),
                    productId: $link->getProductId(),
                    priceDate: $query->priceDate
                )
            );
        }

        return null;
    }
}
