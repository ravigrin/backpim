<?php
declare(strict_types=1);

namespace Mobzio\Domain\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;

#[ORM\Entity]
#[ORM\Table('mobzio_full_statistic')]
#[ORM\HasLifecycleCallbacks]
class FullStatistic extends AggregateRoot
{
    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private DateTime $updatedAt;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: "uuid", unique: true)]
        private string $fullStatisticId,
        #[ORM\Column]
        private string $linkId,
        #[ORM\Column]
        private string $addTime,
        #[ORM\Column(length: 2000)]
        private string $userAgent,
        #[ORM\Column]
        private bool   $isMobile,
        #[ORM\Column]
        private string $hash
    )
    {
    }

    /**
     * Gets triggered only on insert
     */
    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTime("now");
    }

    /**
     * Gets triggered every time on update
     */
    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTime("now");
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function getFullStatisticId(): string
    {
        return $this->fullStatisticId;
    }

    public function setFullStatisticId(string $fullStatisticId): void
    {
        $this->fullStatisticId = $fullStatisticId;
    }

    public function getLinkId(): string
    {
        return $this->linkId;
    }

    public function setLinkId(string $linkId): void
    {
        $this->linkId = $linkId;
    }

    public function getAddTime(): string
    {
        return $this->addTime;
    }

    public function setAddTime(string $addTime): void
    {
        $this->addTime = $addTime;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    public function isMobile(): bool
    {
        return $this->isMobile;
    }

    public function setIsMobile(bool $isMobile): void
    {
        $this->isMobile = $isMobile;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }
}
