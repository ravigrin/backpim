<?php

namespace Mobzio\Application\Query\Statistic\Dto;

use Mobzio\Domain\Entity\FullStatistic;

class FullStatisticDto
{
    public function __construct(
        public string $userAgent,
        public string $addTime,
        public bool   $isMobile
    )
    {
    }

    public static function getDto(FullStatistic $statistic): self
    {
        return new self(
            userAgent: $statistic->getUserAgent(),
            addTime: date('d.m.Y H:i:s', (int)$statistic->getAddTime()),
            isMobile: $statistic->isMobile()
        );
    }
}