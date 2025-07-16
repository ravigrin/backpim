<?php

namespace Mobzio\Domain\Repository\Dto;

final class MyLinkResponseDto
{
    public function __construct(
        public string                 $linkId,
        public string                 $link,
        public string                 $shortcode,
        public string                 $originalLink,
        public ?StatsShortResponseDto $stats = null,
        public ?string                $description = null,
        public ?FolderResponseDto     $folder = null
    )
    {
    }
}
