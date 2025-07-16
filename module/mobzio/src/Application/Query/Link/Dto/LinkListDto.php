<?php

namespace Mobzio\Application\Query\Link\Dto;

use Mobzio\Domain\Entity\Link;

class LinkListDto
{
    public function __construct(
        public string             $linkId,
        public string             $link,
        public ?string            $productId = null,
        public ?string            $campaign = null,
        public ?string            $blogger = null,
        public ?string            $phrase = null,
        public ?StatsWithSalesDto $stats = null
    )
    {
    }

    /**
     * @param Link $link
     * @param StatsWithSalesDto|null $stats
     * @return self
     */
    public static function getDto(Link $link, ?StatsWithSalesDto $stats = null): self
    {
        return new self(
            linkId: $link->getLinkId(),
            link: $link->getLink(),
            productId: $link->getProductId(),
            campaign: $link->getCampaign(),
            blogger: $link->getBlogger(),
            phrase: $link->getPhrase(),
            stats: $stats
        );
    }
}
