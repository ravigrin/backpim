<?php

namespace Mobzio\Domain\Repository\Dto;

final class StatsDto
{
    public function __construct(
        // timestamp
        public string $addTime,
        public string $userAgent,
        public string $isMobile
    )
    {
    }
}
