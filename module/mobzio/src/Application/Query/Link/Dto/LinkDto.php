<?php

namespace Mobzio\Application\Query\Link\Dto;

use Mobzio\Domain\Entity\Link;

class LinkDto
{
    public function __construct(
        public string  $linkId,
        public int     $mobzioLinkId,
        public string  $link,
        public string  $shortcode,
        public string  $originalLink,
        public ?string $campaign = null,
        public ?string $blogger = null,
        public ?string $source = null,
        public ?string $medium = null,
        public ?string $description = null,
        public ?string $productId = null,
        public ?string $phrase = null,
        public ?string $linkType = null,
        public ?string $folderId = null,
        public ?string $folderName = null,
        public ?string $folderDescription = null,
        public ?StatsWithSalesDto $stat = null
    )
    {
    }

    public static function getDto(Link $link, ?StatsWithSalesDto $stat = null): self
    {
        return new self(
            linkId: $link->getLinkId(),
            mobzioLinkId: $link->getMobzioLinkId(),
            link: $link->getLink(),
            shortcode: $link->getShortcode(),
            originalLink: $link->getOriginalLink(),
            campaign: $link->getCampaign(),
            blogger: $link->getBlogger(),
            source: $link->getSource(),
            medium: $link->getMedium(),
            description: $link->getDescription(),
            productId: $link->getProductId(),
            phrase: $link->getPhrase(),
            linkType: $link->getLinkType(),
            folderId: $link->getFolderId(),
            folderName: $link->getFolderName(),
            folderDescription: $link->getFolderDescription(),
            stat: $stat
        );
    }
}
