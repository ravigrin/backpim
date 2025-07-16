<?php

namespace Mobzio\Domain\Repository;

use Mobzio\Domain\Entity\Link;

interface LinkRepositoryInterface
{
    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     * @return Link[]
     */
    public function findBy(
        array  $criteria,
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): array;

    /**
     * @param array<array-key,mixed> $criteria
     * @param array<string,string>|null $orderBy
     */
    public function findOneBy(
        array  $criteria,
        ?array $orderBy = null,
    ): ?Link;

    /**
     * Возвращает общее кол-во всех ссылок
     * @return int
     */
    public function getRowsCount(): int;

    /**
     * Возвращает массив идентификаторов ссылок с артикулами
     * @return array{}
     */
    public function getLinkIdsWithVendorCode(): array;
}