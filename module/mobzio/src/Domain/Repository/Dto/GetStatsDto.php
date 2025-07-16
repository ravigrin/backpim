<?php

namespace Mobzio\Domain\Repository\Dto;

final class GetStatsDto
{
    public function __construct(
        // Идентификатор ссылки Mobzio
        public int     $linkId,
        // Опциональный timestamp фильтр, по-умолчанию 1640984404 (1 January 2022 00:00 MSK)
        public ?string $dateFrom = null,
        // Опциональный timestamp фильтр, по-умолчанию текущее время
        public ?string $dateTo = null
    )
    {
    }
}
