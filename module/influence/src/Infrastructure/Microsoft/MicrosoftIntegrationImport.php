<?php

namespace Influence\Infrastructure\Microsoft;

use GuzzleHttp\Exception\GuzzleException;
use Influence\Domain\Repository\MicrosoftIntegrationInterface;
use Influence\Infrastructure\Microsoft\Resources\Token;
use Microsoft\Graph\Exception\GraphException;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

readonly class MicrosoftIntegrationImport implements MicrosoftIntegrationInterface
{
    public function __construct(
        private Token $token,
    ) {
    }

    /**
     * @throws GuzzleException
     * @throws GraphException
     */
    public function getData(
        string $drive,
        string $item,
        string $worksheet,
        string $range = ""
    ): array {
        $graph = new Graph();
        $graph->setAccessToken($this->token->get());

        if ($range === "") {
            $format = "/drives/%s/items/%s/workbook/worksheets('%s')/usedRange";
        } else {
            $format = "/drives/%s/items/%s/workbook/worksheets('%s')/Range(address='" . $range . "')";
        }
        $endpoint = sprintf($format, $drive, $item, $worksheet);

        $rangeToUpdate = $graph->createRequest(
            requestType: "GET",
            endpoint: $endpoint
        )->setReturnType(Model\WorkbookRange::class)->execute();
        return $rangeToUpdate->getText();
    }

    /**
     * @throws GraphException
     * @throws GuzzleException
     */
    public function getDataFormat(
        string $drive,
        string $item,
        string $worksheet,
        string $range = ""
    ): array {
        $table = $this->getData($drive, $item, $worksheet, $range);
        $index = [];
        foreach (array_shift($table) as $row => $value) {
            if ($value) {
                $index[$row] = $value;
            }
        }
        $tableData = [];
        foreach ($table as $column => $row) {
            if (count(array_filter($row)) === 0) {
                continue;
            }
            foreach ($index as $key => $title) {
                $tableData[$column][$title] = $row[$key];
            }
        }
        return array_values($tableData);
    }
}
