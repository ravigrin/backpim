<?php

declare(strict_types=1);

namespace Mobzio\Application\Query\Link\GetLastOne;

use Mobzio\Domain\Repository\LinkCreateLogRepositoryInterface;
use Mobzio\Infrastructure\Exception\HashNotFound;
use Shared\Domain\Query\QueryHandlerInterface;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private LinkCreateLogRepositoryInterface $linkCreateLogRepository

    )
    {
    }

    /**
     * @param Query $query
     * @return array{'result': string, 'message': string, 'link' => string}
     * @throws HashNotFound
     */
    public function __invoke(Query $query): array
    {
        if (!$createLog = $this->linkCreateLogRepository->findOneBy(['hash' => $query->hash])) {
            throw new HashNotFound('Created link data log by hash not found 
            - module/mobzio/src/Application/Query/Link/GetLastOne/Fetcher.php::invoke()');
        }

        return [
            'result' => $createLog->getStatus(),
            'link' => $createLog->getMessage()
        ];
    }
}
