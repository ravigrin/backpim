<?php

namespace Mobzio\Domain\Service;

use Doctrine\DBAL\Exception;
use Mobzio\Domain\Repository\FullStatisticRepositoryInterface;
use Mobzio\Domain\Repository\LinkRepositoryInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

readonly class ExcelService
{
    public function __construct(
        private StatisticService                 $statisticService,
        private LinkRepositoryInterface          $linkRepository,
        private FullStatisticRepositoryInterface $fullStatisticRepository
    )
    {
    }


    /**
     * Формирует Exel файл с данными по списку ссылок Mobzio
     * @return false|string
     * @throws Exception
     */
    public function getLinkAll(): false|string
    {
        $links = $this->linkRepository->findBy([]);
        $stats = $this->statisticService->getStatsWithSales(date('Y-m-d', strtotime('-1 day')));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Список ссылкок");

        $sheet->setCellValue('A1', 'ID продукта');
        $sheet->setCellValue('B1', 'ID ссылки Mobzio');
        $sheet->setCellValue('C1', 'Короткая ссылка');
        $sheet->setCellValue('D1', 'Полная ссылка');
        $sheet->setCellValue('E1', 'Поисковая фраза');
        $sheet->setCellValue('F1', 'Campaign');
        $sheet->setCellValue('G1', 'Блогер');
        $sheet->setCellValue('H1', 'Дата создания');
        $sheet->setCellValue('I1', 'Цена товара');
        $sheet->setCellValue('J1', 'Всего продано');
        $sheet->setCellValue('K1', 'Среднее кол-во продаж за месяц');
        $sheet->setCellValue('L1', 'Всего переходов по ссылке');
        $sheet->setCellValue('M1', 'Артикул');

        $i = 2;
        foreach ($links as $link) {
            $statKey = array_search($link->getLinkId(), array_column($stats, 'linkId'));

            $sheet->setCellValue("A$i", $link->getProductId());
            $sheet->setCellValue("B$i", $link->getMobzioLinkId());
            $sheet->setCellValue("C$i", $link->getLink());
            $sheet->setCellValue("D$i", $link->getOriginalLink());
            $sheet->setCellValue("E$i", $link->getPhrase());
            $sheet->setCellValue("F$i", $link->getCampaign());
            $sheet->setCellValue("G$i", $link->getBlogger());
            $sheet->setCellValue("H$i", $link->getCreatedAt());
            $sheet->setCellValue("I$i", $stats[$statKey]?->sellerPrice);
            $sheet->setCellValue("J$i", $stats[$statKey]?->salesCount);
            $sheet->setCellValue("K$i", $stats[$statKey]?->monthAverageSales);
            $sheet->setCellValue("L$i", $stats[$statKey]?->totalLinkFollows);
            $sheet->setCellValue("M$i", $stats[$statKey]?->vendorCode);
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), date('d-m-Y-H-i-s'));
        $writer->save($tempFile);

        return $tempFile;
    }

    /**
     * Формирует Exel файл с данными статистики по id ссылки
     * @param string $linkId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @return false|string
     */
    public function getLinkStat(string $linkId, ?string $dateFrom = null, ?string $dateTo = null): false|string
    {
        if (!$link = $this->linkRepository->findOneBy(['linkId' => $linkId])) {
            return false;
        }

        $stat = $this->statisticService->getStatsWithSalesDto(
            linkId: $linkId,
            productId: $link->getProductId()
        );

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Статистика по ссылке");

        $sheet->setCellValue('E1', 'Цена товара');
        $sheet->setCellValue('F1', 'Всего продано');
        $sheet->setCellValue('G1', 'Среднее кол-во продаж за месяц');
        $sheet->setCellValue('H1', 'Всего переходов по ссылке');
        $sheet->setCellValue('E2', $stat?->sellerPrice);
        $sheet->setCellValue('F2', $stat?->salesCount);
        $sheet->setCellValue('G2', $stat?->monthAverageSales);
        $sheet->setCellValue('H2', $stat?->totalLinkFollows);

        if ($linkData = $this->fullStatisticRepository->getByLink(
            linkId: $linkId,
            dateFrom: $dateFrom,
            dateTo: $dateTo
        )) {
            $sheet->setCellValue('A1', 'Дата перехода');
            $sheet->setCellValue('B1', 'User Agent');
            $sheet->setCellValue('C1', 'Переход с мобильного');
            $i = 2;
            foreach ($linkData as $link) {
                $sheet->setCellValue("A$i", $link->addTime);
                $sheet->setCellValue("B$i", $link->userAgent);
                $sheet->setCellValue("C$i", $link->isMobile ? 'Да' : 'Нет');
                $i++;
            }
        }

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), date('d-m-Y-H-i-s'));
        $writer->save($tempFile);

        return $tempFile;
    }
}
