<?php

namespace OneC\Infrastructure\Guzzle;

use OneC\Domain\Repository\BrandInterface;

class BrandRepository implements BrandInterface
{
    public function isBrandUpload(string $brandId): bool
    {
        // TODO: Implement isBrandUpload() method.
    }

    public function exportBrand(string $brandId, string $brandName, bool $isUpdate): void
    {
        // TODO: Implement exportBrand() method.
    }
}
