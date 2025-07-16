<?php

namespace Mobzio\Domain\Repository;

use Mobzio\Domain\Repository\Dto\MyLinkResponseDto;
use Mobzio\Domain\Repository\Dto\GetStatsDto;
use Mobzio\Domain\Repository\Dto\StatsDto;

/**
 * Репозиторий для получения данных по ссылам Mobzio с DWH
 */
interface DwhRepositoryInterface
{
    /**
     * Получить информацию о всех ссылках
     * @param bool|null $stats Если нужно возвращать статистику  - true, по-умолчанию false
     * @return MyLinkResponseDto[]
     */
    public function getMyLinks(?bool $stats = false): array;


    /**
     * Статистика по определенной ссылке
     * @param GetStatsDto $statsDto
     * @return StatsDto[]
     */
    public function getStats(GetStatsDto $statsDto): array;


    /**
     * Возвращает цену товара на WB на указанную дату
     * @param string $vendorCode
     * @param string|null $date
     * @return int|null
     */
    public function getSellerPrice(string $vendorCode, ?string $date = null): ?int;

    /**
     * Возвращает количество продаж товара на WB
     * @param string $vendorCode
     * @return int|null
     */
    public function getOneCalesCount(string $vendorCode): ?int;

    /**
     * Возвращает количество продаж товаров на WB
     * @return array|null
     */
    public function getSalesCount(): ?array;

    /**
     * Возвращает среднее количество продаж за последние 3 мес по всем артикулам
     * @return array|null
     */
    public function getMonthAverageSales(): ?array;

    /**
     * @param $vendorCode
     * @return int|null
     */
    public function getMonthAverageSale($vendorCode): ?int;
}