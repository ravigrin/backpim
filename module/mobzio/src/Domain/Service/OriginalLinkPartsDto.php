<?php

namespace Mobzio\Domain\Service;

class OriginalLinkPartsDto
{
    public function __construct(
        public ?int    $nmId = null,
        public ?string $phrase = null,
        public ?string $source = null,
        public ?string $medium = null,
        public ?string $campaign = null,
        public ?string $blogger = null,

    )
    {
    }
}