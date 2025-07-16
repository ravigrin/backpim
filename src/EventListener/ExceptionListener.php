<?php

declare(strict_types=1);

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ExceptionListener implements EventSubscriberInterface
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    /**
     * @throws \JsonException
     */
    public function processException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        $this->logger->error($throwable->getMessage(), $throwable->getTrace());

        $event->setResponse(
            JsonResponse::fromJsonString(
                data: json_encode(['error' => $throwable->getMessage()]),
                status: 500
            )
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'processException',
        ];
    }
}
