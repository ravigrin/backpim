<?php
declare(strict_types=1);

namespace Mobzio\Application\Command\Import\Stat;

use Mobzio\Domain\Entity\FullStatistic;
use Mobzio\Domain\Repository\Dto\GetStatsDto;
use Mobzio\Domain\Repository\DwhRepositoryInterface;
use Mobzio\Domain\Repository\FullStatisticRepositoryInterface;
use Mobzio\Domain\Repository\LinkRepositoryInterface;
use Mobzio\Domain\Repository\MobzioRepositoryInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Repository\PersistenceInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private LoggerInterface                  $logger,
        private LinkRepositoryInterface          $linkRepository,
        private DwhRepositoryInterface           $dwhRepository,
        private MobzioRepositoryInterface        $mobzioRepository,
        private PersistenceInterface             $persistenceRepository,
        private FullStatisticRepositoryInterface $fullStatisticRepository
    )
    {
    }

    public function __invoke(Command $command): void
    {
        if (!$links = $this->linkRepository->findBy([])) {
            $this->logger->critical("Not FOUND Links! 
                - mobzio/src/Application/Command/Import/Stat/Handler.php::invoke()");
            return;
        }

        foreach ($links as $link) {

            $stats = match ($command->source) {
                "api" => $this->mobzioRepository->getStats(new GetStatsDto(linkId: $link->getMobzioLinkId())),
                "dwh" => $this->dwhRepository->getStats(new GetStatsDto(linkId: $link->getMobzioLinkId())),
            };

            foreach ($stats as $stat) {
                $hash = md5($link->getLinkId() . $stat->addTime . $stat->userAgent . $stat->isMobile);
                if ($this->fullStatisticRepository->findOneBy(['hash' => $hash])) {
                    continue;
                }

                $statistic = new FullStatistic(
                    fullStatisticId: Uuid::uuid7()->toString(),
                    linkId: $link->getLinkId(),
                    addTime: $stat->addTime,
                    userAgent: $stat->userAgent,
                    isMobile: (bool)$stat->isMobile,
                    hash: $hash
                );
                $this->persistenceRepository->persist($statistic);
            }

            $this->persistenceRepository->flush();

            // TODO: Костыль - cURL может упасть с - error 6
            if ($command->source == 'api') {
                sleep(1);
            }
        }
    }
}
