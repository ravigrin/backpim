<?php

namespace Mobzio\Domain\Repository\Dto;

final class StatsFullResponseDto
{
    public function __construct(
        /**
         * @var StatsDto[]
         */
        public array $stats,
        public int   $page,
        public int   $nextPage,
        public int   $totalPages,
        public int   $totalClicks
    )
    {
    }
}
