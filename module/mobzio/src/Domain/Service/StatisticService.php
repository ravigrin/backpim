<?php

namespace Mobzio\Domain\Service;

use Doctrine\DBAL\Exception;
use Mobzio\Application\Query\Link\Dto\StatsWithSalesDto;
use Mobzio\Domain\Repository\DwhRepositoryInterface;
use Mobzio\Domain\Repository\LinkRepositoryInterface;
use Mobzio\Domain\Repository\StatisticRepositoryInterface;
use Shared\Domain\Query\QueryBusInterface;
use Wildberries\Application\Query\Product\GetVendorCodeByUuid\Query as GetVendorCodeByUuid;

readonly class StatisticService
{
    public function __construct(
        private StatisticRepositoryInterface $statisticRepository,
        private LinkRepositoryInterface      $linkRepository,
        private DwhRepositoryInterface       $dwhRepository,
        private QueryBusInterface            $queryBus
    )
    {
    }

    /**
     * Возвращает данные статистики по ссылке
     * @param string $linkId
     * @param string $productId
     * @param string|null $priceDate
     * @return StatsWithSalesDto|null
     */
    public function getStatsWithSalesDto(string $linkId, string $productId, ?string $priceDate = null): ?StatsWithSalesDto
    {
        if (!$vendorCode = $this->queryBus->dispatch(new GetVendorCodeByUuid($productId))) {
            return null;
        }

        return StatsWithSalesDto::getDto(
            stats: $this->statisticRepository->findOneBy(['linkId' => $linkId]),
            sellerPrice: $this->dwhRepository->getSellerPrice(
                vendorCode: $vendorCode,
                date: $priceDate
            ),
            salesCount: $this->dwhRepository->getOneCalesCount($vendorCode),
            monthAverageSales: $this->dwhRepository->getMonthAverageSale($vendorCode),
        );
    }

    /**
     * Формирует массив DTO статистики с ценами и продажами по всем артикулам ссылок
     * @param string $date
     * @return StatsWithSalesDto[]|null
     * @throws Exception
     */
    public function getStatsWithSales(string $date): ?array
    {
        $response = null;
        $monthAverage = $this->dwhRepository->getMonthAverageSales();
        $prices = $this->dwhRepository->getSellerPriceList($date);
        $sales = $this->dwhRepository->getSalesCount();
        /**
         * @var array{'link_id': string, 'vendor_code': string} $linkCode
         */
        foreach ($this->linkRepository->getLinkIdsWithVendorCode() as $linkCode) {
            $priceKey = array_search($linkCode['vendor_code'], array_column($prices, 'offer_id'));
            $monthAvrKey = array_search($linkCode['vendor_code'], array_column($monthAverage, 'offerId'));
            $salesKey = array_search($linkCode['vendor_code'], array_column($sales, 'offerId'));
            $response[] = StatsWithSalesDto::getDto(
                linkId: $linkCode['link_id'],
                vendorCode: $linkCode['vendor_code'],
                stats: $this->statisticRepository->findOneBy(['linkId' => $linkCode['link_id']]),
                sellerPrice: $prices[$priceKey]['final_price'] ?? null,
                priceDate: $prices[$priceKey]['price_date'] ?? null,
                salesCount: (int)$prices[$salesKey]['price_date'] ?? null,
                monthAverageSales: $monthAverage[$monthAvrKey]['avg_cnt'] ?? null
            );
        }

        return $response;
    }

}
