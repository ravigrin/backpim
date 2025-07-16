<?php

namespace Ozon\Domain\Listener;

use Ozon\Domain\Event\ProductAttributeHistoryAdded;
use Ozon\Domain\Repository\ProductAttributeInterface;
use Ozon\Domain\Repository\ProductInterface;
use Shared\Domain\Event\EventHandlerInterface;
use Shared\Domain\Exception\ValueObjectException;
use Shared\Domain\Repository\PersistenceInterface;

readonly class ProductAttributeHistoryListener implements EventHandlerInterface
{
    public function __construct(
        private PersistenceInterface      $persistenceRepository,
        private ProductInterface          $productRepository,
        private ProductAttributeInterface $productAttribute,
    ) {
    }

    /**
     * @throws ValueObjectException
     */
    public function __invoke(ProductAttributeHistoryAdded $event): void
    {
        $product = $this->productRepository->findOneByCriteria(['uuid' => $event->productId]);
        if (is_null($product)) {
            //TODO: log
            return;
        }

        $productAttribute = $this->productAttribute->findOneByCriteria([
            'productUuid' => $event->productId,
            'attributeUuid' => $event->attributeId
        ]);

        if (is_null($productAttribute)) {
            //TODO: log
            return;
        }

    }
}
