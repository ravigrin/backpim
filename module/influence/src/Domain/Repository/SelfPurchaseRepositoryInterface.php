<?php

namespace Influence\Domain\Repository;

interface SelfPurchaseRepositoryInterface
{
    /**
     * @param mixed[] $documents
     */
    public function save(array $documents): void;

}
