<?php

declare(strict_types=1);

namespace Pim\EndPoint\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'pim:product:build-file',
    description: 'СОздать csv файл товаров',
)] final class ProductBuildFileCommand extends Command
{
    public function __construct(
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $brandsUuid = [
            'To My Skin' => 'a1e6d36b-0e87-11ee-890a-ac1f6b72b9b1',
            'Anuka' => '3ad97837-0e7f-11ee-890a-ac1f6b72b9b1',
            'EcoHarmony' => '438648c6-2d21-11ee-8c1e-81e0f3caa235',
            'HAIROSA' => 'cf1e6d26-0e87-11ee-890a-ac1f6b72b9b1',
            'Meditaura' => '0347f862-34ff-11ee-8c1e-81e0f3caa235',
            'Lanolique' => '2707536e-34ff-11ee-8c1e-81e0f3caa235',
            'CLEANPLUS' => '7fde39b4-52d8-11ee-8c20-bc6a09a24683',
            'Cosmetime' => 'c39a86a5-27a0-11ee-8c1e-81e0f3caa235',
        ];

        $productLinesUuid = [
            'TO MY SKIN' => '018c8a4c-e4d4-709f-b12e-c52bbf88b97e',
            'BEAUTYPHORIA' => '018c8a4c-ff7e-7391-90f5-87fd144f8a2e',
            'Laboratory' => '018c8a4d-c70c-73fb-88c1-f44ac2fb31ef',
            'O! My Face' => '018c8a4d-a69f-719c-b8b0-8ebe666ab2b9',
            'EcoHarmony' => '018c8a4d-874d-7088-ab13-27defdcb6049',
            'Cosmetime' => '018c8a4d-672d-73e1-8d18-5d0249ad8db4',
            'HAIROSA PROFESSIONAL' => '018c8a4d-3617-704f-b253-8c177e401625',
        ];

        $units = [
            '01' => '018c5782-e31a-73e3-abbc-19d888763137',
            '02' => '018c5782-e330-73e4-9d3a-73c94925824c'
        ];

        $brands = [
            'TMS' => ['To My Skin', [
                '002' => 'TO MY SKIN',
                '003' => 'BEAUTYPHORIA',
                '004' => 'Laboratory',
                '005' => 'O! My Face',
            ]],
            'ANK' => ['Anuka'],
            'AKS' => ['Anuka'],
            'ECH' => ['EcoHarmony'],
            'HRS' => ['HAIROSA', [
                '004' => 'HAIROSA PROFESSIONAL',
                '005' => 'HAIROSA PROFESSIONAL',
                '006' => 'HAIROSA PROFESSIONAL',
            ]],
            'MDT' => ['Meditaura'],
            'LNQ' => ['Lanolique'],
            'CNP' => ['CLEANPLUS'],
            'NOB' => ['CLEANPLUS'],
            'CST' => ['Cosmetime', [
                '001' => 'EcoHarmony',
                '002' => 'Cosmetime',
            ]],
        ];

        $products = file(__DIR__ . '/file/tmp-products.csv', FILE_SKIP_EMPTY_LINES);
        if (empty($products)) {
            $output->writeln('product file empty');
            return self::FAILURE;
        }

        $buildProducts = [];

        foreach ($products as $product) {
            /** @var string[] $data */
            $data = str_getcsv($product);
            if (empty($data[1])) {
                continue;
            }

            list($productUuid, $productName, $vendorCode, $brandUuid, $productType) = $data;

            if (empty($vendorCode)) {
                $output->writeln("empty vendorCode");
                continue;
            }

            if (strlen($vendorCode) !== 12) {
                $buildProducts[] = [
                    $productUuid, $productName, $vendorCode, $brandUuid, $productType,
                ];
                continue;
            }

            $unitCode = substr($vendorCode, offset: 0, length: 2);
            $brandCode = substr($vendorCode, offset: 2, length: 3);
            $productLineCode = substr($vendorCode, offset: 5, length: 3);

            $unitUuid = $units[$unitCode];

            $brandName = $brands[strtoupper($brandCode)][0];
            $brandUuid = $brandsUuid[$brandName];

            $productLineName = null;
            $productLineUuid = '';
            if ($productLineCode !== '000') {
                $productLineName = $brands[strtoupper($brandCode)][1][$productLineCode];
                $productLineUuid = $productLinesUuid[$productLineName];
            }

            echo sprintf(
                'u:%s %s:%s:%s %s:%s:%s',
                $unitCode,
                $brandCode,
                $brandName,
                $brandUuid,
                $productLineCode,
                $productLineName,
                $productLineUuid
            ) . PHP_EOL;

            $buildProducts[] = [
                $productUuid, $productName, $vendorCode, $brandUuid, $productType, $unitUuid, $productLineUuid,
            ];

            //            $brand = $this->brandRepository->findOneByCriteria(['brandId' => $brandUuid]);
            //
            //            $product = $this->productRepository->findByVendorCode($vendorCode);
        }

        $fp = fopen(__DIR__ . '/file/products.csv', 'w');

        foreach ($buildProducts as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        return self::SUCCESS;
    }
}
