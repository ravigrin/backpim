<?php

namespace Mobzio\Infrastructure\Dwh;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Mobzio\Domain\Repository\Dto\GetStatsDto;
use Mobzio\Domain\Repository\Dto\MyLinkResponseDto;
use Mobzio\Domain\Repository\Dto\StatsDto;
use Mobzio\Domain\Repository\Dto\StatsShortResponseDto;
use Mobzio\Domain\Repository\DwhRepositoryInterface;

/**
 * Репозиторий для получения данных о сылках с DWH
 */
class DwhRepository implements DwhRepositoryInterface
{
    private Connection $connection;

    public function __construct(
        private readonly ManagerRegistry $doctrine,
    )
    {
        /** @var Connection $dwh */
        $dwh = $this->doctrine->getConnection('dwh');
        $this->connection = $dwh;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getMyLinks(?bool $stats = false): array
    {
        $contents = $this->connection->executeQuery(
            sql: "SELECT ML.link_id, ML.link, ML.shortcode, ML.original_link, ML.stats_today, ML.stats_yesterday, ML.stats_all, ML.description FROM mobz.mylinks ML"
        )->fetchAllAssociative();

        return array_map(fn(array $content) => new MyLinkResponseDto(
            linkId: $content['link_id'],
            link: $content['link'],
            shortcode: $content['shortcode'],
            originalLink: $content['original_link'],
            stats: $stats
                ? new StatsShortResponseDto(
                    today: (int)$content['stats_today'],
                    yesterday: (int)$content['stats_yesterday'],
                    all: (int)$content['stats_all'])
                : null,
            description: (!empty($content['description'])) ? $content['description'] : null
        ), $contents);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getStats(GetStatsDto $statsDto): array
    {
        $contents = $this->connection->executeQuery(
            sql: "select S.user_agent, S.addTimestamp, S.isMobile from mobz.stats S where link_id = ?",
            params: [$statsDto->linkId]
        )->fetchAllAssociative();

        return array_map(fn(array $content) => new StatsDto(
            addTime: $content['addTimestamp'],
            userAgent: $content['user_agent'],
            isMobile: $content['isMobile']
        ), $contents);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getSellerPrice(string $vendorCode, ?string $date = null): ?int
    {
        if ($date) {
            $price = $this->connection->executeQuery(
                sql: "SELECT PH.final_price FROM pbi.wb_price_history PH 
                      WHERE PH.date <= ? AND PH.offer_id = ?
                      ORDER BY PH.date DESC",
                params: [$date, $vendorCode]
            )->fetchOne();
            if (!$price) {
                $price = $this->connection->executeQuery(
                    sql: "SELECT PH.final_price FROM pbi.wb_price_history PH 
                          WHERE PH.date >= ? AND PH.offer_id = ?
                          ORDER BY PH.date",
                    params: [$date, $vendorCode]
                )->fetchOne();
            }
            return $price;
        } else {
            return (int)$this->connection->executeQuery(
                sql: "SELECT PH.final_price FROM pbi.wb_price_history PH 
                      WHERE PH.offer_id = ? 
                      ORDER BY PH.date",
                params: [$vendorCode]
            )->fetchOne();
        }
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getSellerPriceList(string $date): ?array
    {
        return $this->connection->executeQuery(
            sql: "SELECT PH.offer_id, PH.final_price, max(PH.date) as price_date FROM pbi.wb_price_history PH
                    WHERE PH.offer_id IS NOT NULL AND (PH.date = ? OR PH.date =
                    (select max(date) from pbi.wb_price_history where offer_id=PH.offer_id and date between dateadd(month, -3, convert(date, ?)) and ?)                                                             )
                    group by PH.final_price, PH.offer_id",
            params: [$date, $date, $date]
        )->fetchAllAssociative();
    }


    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getSalesCount(): array
    {
        return $this->connection->executeQuery(
            sql: "SELECT Z.offerId, COUNT(Z.orderId) count FROM pbi.Заказы Z
                    WHERE Z.МП = 'WB'
                    GROUP BY Z.offerId"
        )->fetchAllAssociative();
    }


    /**
     * @param string $vendorCode
     * @return int|null
     * @throws Exception
     */
    public function getOneCalesCount(string $vendorCode): ?int
    {
        return (int)$this->connection->executeQuery(
            sql: "SELECT COUNT(Z.orderId) count FROM pbi.Заказы Z
                    WHERE Z.МП = 'WB' AND Z.offerId = ?
                    GROUP BY Z.offerId",
            params: [$vendorCode]
        )->fetchOne();
    }


    /**
     * @throws Exception
     */
    public function getMonthAverageSales(): array
    {
        return $this->connection->executeQuery(
            sql: "select offerId
                     , avg(cnt) as avg_cnt
                  from (select datepart(year, orderDate) as y
                             , datepart(month, orderDate) as m
                             , offerId
                             , sum(wbQuantity) as cnt
                          from pbi.Заказы
                         where Тип_заказа = N'Клиентский'
                           and orderDate >= dateadd(month, -3, convert(date, dateadd(day, 1 - datepart(day, getdate()),  getdate())))
                           and orderDate < convert(date, dateadd(day, 1 - datepart(day, getdate()),  getdate()))
                         group by datepart(year, orderDate)
                                , datepart(month, orderDate)
                                , offerId
                       ) as t1
                 group by offerId"
        )->fetchAllAssociative();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getMonthAverageSale($vendorCode): ?int
    {
        return (int)$this->connection->executeQuery(
            sql: "select avg(cnt) as avg_cnt
                  from (select datepart(year, orderDate) as y
                             , datepart(month, orderDate) as m
                             , offerId
                             , sum(wbQuantity) as cnt
                          from pbi.Заказы
                         where Тип_заказа = N'Клиентский'
                           and offerId = ?
                           and orderDate >= dateadd(month, -3, convert(date, dateadd(day, 1 - datepart(day, getdate()),  getdate())))
                           and orderDate < convert(date, dateadd(day, 1 - datepart(day, getdate()),  getdate()))
                         group by datepart(year, orderDate)
                                , datepart(month, orderDate)
                                , offerId
                       ) as t1",
            params: [$vendorCode]
        )->fetchOne();
    }
}
