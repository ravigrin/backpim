<?php

namespace Influence\Domain\Repository;

interface MicrosoftIntegrationInterface
{
    /**
     * @return mixed[]
     */
    public function getData(
        string $drive,
        string $item,
        string $worksheet,
        string $range
    ): array;

    /**
     * @return mixed[]
     */
    public function getDataFormat(
        string $drive,
        string $item,
        string $worksheet,
        string $range
    ): array;

}
