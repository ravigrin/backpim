<?php

namespace Mobzio\Application\Query\Statistic\Dto;

use DateTime;
use Mobzio\Domain\Entity\Statistic;

class StatisticDto
{
    public string $statisticId;

    public ?string $linkId = null;
    public ?int $today = null;

    public ?int $yesterday = null;

    public ?int $allTime = null;

    public DateTime $createdAt;

    public ?DateTime $updatedAt = null;


    public static function getDto(Statistic $statistic): self
    {
        $dto = new self();
        $dto->statisticId = $statistic->getStatisticId();
        $dto->linkId = $statistic->getLinkId();
        $dto->today = $statistic->getToday();
        $dto->yesterday = $statistic->getYesterday();
        $dto->allTime = $statistic->getAllTime();
        $dto->createdAt = $statistic->getCreatedAt();
        $dto->updatedAt = $statistic->getUpdatedAt();

        return $dto;
    }
}
