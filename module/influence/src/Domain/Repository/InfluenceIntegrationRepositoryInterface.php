<?php

namespace Influence\Domain\Repository;

interface InfluenceIntegrationRepositoryInterface
{
    /**
     * @param mixed[] $documents
     */
    public function save(array $documents): void;

}
