<?php

namespace Mobzio\Domain\Repository;

use Mobzio\Domain\Repository\Dto\AddLinkDto;
use Mobzio\Domain\Repository\Dto\AddLinkResponseDto;
use Mobzio\Domain\Repository\Dto\MyLinkResponseDto;
use Mobzio\Domain\Repository\Dto\GetStatsDto;
use Mobzio\Domain\Repository\Dto\StatsDto;

/**
 * Репозиторий для работы с внешним сервисом для формирования ссылок Mobzio
 */
interface MobzioRepositoryInterface
{
    /**
     * Получить информацию о всех ссылках
     * @param bool|null $stats Если нужно возвращать статистику  - true, по-умолчанию false
     * @return MyLinkResponseDto[]
     */
    public function getMyLinks(?bool $stats = false): array;


    /**
     * Получить информацию о конкретной ссылке
     * @param int $linkId
     * @param bool|null $stats Если нужно возвращать статистику  - true, по-умолчанию false
     * @return MyLinkResponseDto
     */
    public function getOneLink(int $linkId, ?bool $stats = false): MyLinkResponseDto;


    /**
     * Статистика по определенной ссылке
     * @param GetStatsDto $statsDto
     * @return StatsDto[]
     */
    public function getStats(GetStatsDto $statsDto): array;


    /**
     * Создание ссылки
     * @param AddLinkDto $addLinkDto
     * @return AddLinkResponseDto
     */
    public function addLink(AddLinkDto $addLinkDto): AddLinkResponseDto;

}