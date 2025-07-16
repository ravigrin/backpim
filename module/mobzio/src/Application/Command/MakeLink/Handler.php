<?php
declare(strict_types=1);

namespace Mobzio\Application\Command\MakeLink;

use Mobzio\Domain\Entity\Link;
use Mobzio\Domain\Entity\LinkCreateLog;
use Mobzio\Domain\Repository\Dto\AddLinkDto;
use Mobzio\Domain\Service\LinkService;
use Mobzio\Infrastructure\Exception\EmptyMessageResponse;
use Mobzio\Infrastructure\Exception\LinkTypeNotSupported;
use Mobzio\Infrastructure\Exception\ResponseFail;
use Mobzio\Infrastructure\Mobzio\MobzioRepository;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Query\QueryBusInterface;
use Shared\Domain\Service\EntityStoreService;
use Wildberries\Application\Query\Product\GetCodeAndLinkByUuid\Query as GetCodeAndLinkByUuid;
use Ramsey\Uuid\Uuid;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private LoggerInterface    $logger,
        private QueryBusInterface  $queryBus,
        private LinkService        $linkService,
        private MobzioRepository   $mobzioRepository,
        private EntityStoreService $entityStoreService
    )
    {
    }

    /**
     * @throws ClientExceptionInterface
     * @throws EmptyMessageResponse
     * @throws LinkTypeNotSupported
     * @throws ResponseFail
     */
    public function __invoke(Command $command): void
    {
        if (!$codeLink = $this->queryBus->dispatch(new GetCodeAndLinkByUuid($command->productId))) {
            $this->logger->critical("Not found vendorCode and publicLink in WB module by Product ID: $command->productId
            - module/mobzio/src/Application/Command/MakeLink/Handler.php::invoke()");
        }

        $baseUrl = preg_replace('/https:\/\/www.|targetUrl=GP/', '', $codeLink->publicLink);
        $phrase = urlencode($command->phrase);
        $source = 'instagram';
        $medium = 'smm';

        $originalLink = $baseUrl
            . 'search=' . $phrase
            . '?targetUrl=EX'
            . '&amp;utm_source=' . $source
            . '&amp;utm_medium=' . $medium
            . '&amp;utm_campaign=' . $codeLink->vendorCode
            . '&amp;utm_content=' . $command->blogger;

        $shortCode = $command->shortcode ?? $this->linkService->getSortCode();

        $newLink = $this->mobzioRepository->addLink(new AddLinkDto(
            web: $originalLink,
            shortcode: $shortCode,
            type: 'wildberries'
        ));

        // Сохраняем ответ от Mobzio
        $linkCreateLog = new LinkCreateLog(
            hash: $command->hash,
            status: $newLink->status,
            message: $newLink->message
        );
        $this->entityStoreService->commit($linkCreateLog);

        // Если успешно создано - создаем ссылку
        if ($newLink == 'success') {
            $link = new Link(
                linkId: Uuid::uuid7()->toString(),
                productId: $command->productId,
                mobzioLinkId: (int)$newLink->linkId,
                link: $newLink->message,
                shortcode: $shortCode,
                phrase: $command->phrase,
                description: null,
                originalLink: $originalLink,
                campaign: $codeLink->vendorCode,
                blogger: $command->blogger,
                source: $source,
                medium: $medium
            );

            $this->entityStoreService->commit($link);
        }
    }
}