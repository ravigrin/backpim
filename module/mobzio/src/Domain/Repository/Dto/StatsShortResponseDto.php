<?php

namespace Mobzio\Domain\Repository\Dto;

final class StatsShortResponseDto
{
    public function __construct(
        public ?int $today = null,
        public ?int $yesterday = null,
        public ?int $all = null
    )
    {
    }
}
