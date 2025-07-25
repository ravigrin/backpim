<?php

declare(strict_types=1);

namespace Shared\Infrastructure\EventBus;

use Shared\Domain\Query\QueryBusInterface;
use Shared\Domain\Query\QueryInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class QueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    /**
     * @psalm-immutable
     */
    public function dispatch(QueryInterface $query): mixed
    {
        return $this->handle($query);
    }
}
