<?php

namespace Mobzio\Application\Query\Link\Dto;

use Mobzio\Domain\Entity\Statistic;

class ShortStatDto
{
    public function __construct(
        public string $statisticId,
        public int    $today,
        public int    $yesterday,
        public int    $all
    )
    {
    }

    /**
     * @param Statistic $stats
     * @return self
     */
    public static function getDto(Statistic $stats): self
    {
        return new self(
            statisticId: $stats->getStatisticId(),
            today: $stats->getToday(),
            yesterday: $stats->getYesterday(),
            all: $stats->getAllTime()
        );
    }
}
