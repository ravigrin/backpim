<?php

namespace Mobzio\Application\Query\Link\Dto;

use Mobzio\Domain\Entity\Statistic;

class StatsWithSalesDto
{
    public function __construct(
        public ?string $linkId = null,
        public ?string $vendorCode = null,
        public ?string $statisticId = null,
        public ?int    $sellerPrice = null,
        public ?string $priceDate = null,
        public ?int    $salesCount = null,
        public ?int    $monthAverageSales = null,
        public ?int    $totalLinkFollows = null
    )
    {
    }

    /**
     * @param string|null $linkId
     * @param string|null $vendorCode
     * @param Statistic|null $stats
     * @param int|null $sellerPrice
     * @param string|null $priceDate
     * @param int|null $salesCount
     * @param int|null $monthAverageSales
     * @return self
     */
    public static function getDto(
        ?string    $linkId = null,
        ?string    $vendorCode = null,
        ?Statistic $stats = null,
        ?int       $sellerPrice = null,
        ?string    $priceDate = null,
        ?int       $salesCount = null,
        ?int       $monthAverageSales = null
    ): self
    {
        return new self(
            linkId: $linkId,
            vendorCode: $vendorCode,
            statisticId: $stats?->getStatisticId(),
            sellerPrice: $sellerPrice,
            priceDate: $priceDate,
            salesCount: $salesCount,
            monthAverageSales: $monthAverageSales,
            totalLinkFollows: $stats?->getAllTime()
        );
    }
}
