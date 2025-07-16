<?php
declare(strict_types=1);

namespace Mobzio\Application\Command\Import\Link;

use Mobzio\Domain\Entity\Link;
use Mobzio\Domain\Entity\Statistic;
use Mobzio\Domain\Repository\Dto\StatsShortResponseDto;
use Mobzio\Domain\Repository\DwhRepositoryInterface;
use Mobzio\Domain\Repository\LinkRepositoryInterface;
use Mobzio\Domain\Repository\MobzioRepositoryInterface;
use Mobzio\Domain\Repository\StatisticRepositoryInterface;
use Mobzio\Domain\Service\LinkService;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Shared\Infrastructure\EventBus\QueryBus;
use Wildberries\Application\Query\Product\GetUuidByNmid\Query as GetUuidByNmidQuery;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private LoggerInterface              $logger,
        private QueryBus                     $queryBus,
        private LinkService                  $linkService,
        private LinkRepositoryInterface      $linkRepository,
        private MobzioRepositoryInterface    $mobzioRepository,
        private DwhRepositoryInterface       $dwhRepository,
        private StatisticRepositoryInterface $statisticRepository,
        private PersistenceInterface         $persistenceRepository,
    )
    {
    }

    public function __invoke(Command $command): void
    {
        $links = match ($command->source) {
            "api" => $this->mobzioRepository->getMyLinks(stats: true),
            "dwh" => $this->dwhRepository->getMyLinks(stats: true),
        };

        foreach ($links as $linkData) {
            $linkParts = $this->linkService->getOriginalLinkParts($linkData->originalLink);

            if (!$linkParts->nmId) {
                $this->logger->critical("Not found nmId in link: 
                - mobzio/src/Application/Command/Import/Handler.php::invoke()");
                continue;
            }

            if (!$productId = $this->queryBus->dispatch(new GetUuidByNmidQuery($linkParts->nmId))) {
                $this->logger->critical("Not FOUND WB Product by nmId: $linkParts->nmId 
                - mobzio/src/Application/Command/Import/Handler.php::invoke()");
            }

            if (!$link = $this->linkRepository->findOneBy(['mobzioLinkId' => $linkData->linkId])) {
                $link = new Link(
                    linkId: Uuid::uuid7()->toString(),
                    productId: $productId,
                    mobzioLinkId: (int)$linkData->linkId,
                    link: $linkData->link,
                    shortcode: $linkData->shortcode,
                    phrase: $linkParts->phrase,
                    description: $linkData->description,
                    originalLink: $linkData->originalLink,
                    campaign: $linkParts->campaign,
                    blogger: $linkParts->blogger,
                    source: $linkParts->source,
                    medium: $linkParts->medium
                );
            } else {
                $link->setProductId($productId);
                $link->setLink($linkData->link);
                $link->setShortcode($linkData->shortcode);
                $link->setPhrase($linkParts->phrase);
                $link->setDescription($linkData->description);
                $link->setOriginalLink($linkData->originalLink);
                $link->setCampaign($linkParts->campaign);
                $link->setBlogger($linkParts->blogger);
                $link->setSource($linkParts->source);
                $link->setMedium($linkParts->medium);
            }

            $this->persistenceRepository->persist($link);

            if ($stats = $linkData->stats) {
                $this->saveStat($link->getLinkId(), $stats);
            }
        }

        $this->persistenceRepository->flush();
    }

    /**
     * @param string $linkId
     * @param StatsShortResponseDto $stats
     * @return void
     */
    private function saveStat(string $linkId, StatsShortResponseDto $stats): void
    {
        if (!$statistic = $this->statisticRepository->findOneBy(['linkId' => $linkId])) {
            $statistic = new Statistic(
                statisticId: Uuid::uuid7()->toString(),
                linkId: $linkId,
                today: $stats->today,
                yesterday: $stats->yesterday,
                allTime: $stats->all
            );
        } else {
            $statistic->setToday($stats->today);
            $statistic->setYesterday($stats->yesterday);
            $statistic->setAllTime($stats->all);
        }

        $this->persistenceRepository->persist($statistic);
    }
}
