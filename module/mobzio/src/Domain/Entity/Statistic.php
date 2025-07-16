<?php
declare(strict_types=1);

namespace Mobzio\Domain\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;

#[ORM\Entity]
#[ORM\Table('mobzio_statistic')]
#[ORM\HasLifecycleCallbacks]
class Statistic extends AggregateRoot
{
    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private DateTime $updatedAt;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: "uuid", unique: true)]
        private string $statisticId,
        #[ORM\Column(type: "uuid", unique: true)]
        private string $linkId,
        #[ORM\Column]
        private ?int   $today,
        #[ORM\Column]
        private ?int   $yesterday,
        #[ORM\Column]
        private ?int   $allTime,
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

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt ?? null;
    }

    public function getStatisticId(): string
    {
        return $this->statisticId;
    }

    public function setStatisticId(string $statisticId): void
    {
        $this->statisticId = $statisticId;
    }

    public function getLinkId(): string
    {
        return $this->linkId;
    }

    public function setLinkId(string $linkId): void
    {
        $this->linkId = $linkId;
    }

    public function getToday(): ?int
    {
        return $this->today;
    }

    public function setToday(?int $today): void
    {
        $this->today = $today;
    }

    public function getYesterday(): ?int
    {
        return $this->yesterday;
    }

    public function setYesterday(?int $yesterday): void
    {
        $this->yesterday = $yesterday;
    }

    public function getAllTime(): ?int
    {
        return $this->allTime;
    }

    public function setAllTime(?int $allTime): void
    {
        $this->allTime = $allTime;
    }
}
