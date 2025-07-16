<?php
declare(strict_types=1);

namespace Mobzio\Domain\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;

#[ORM\Entity]
#[ORM\Table('mobzio_link_create_log')]
#[ORM\HasLifecycleCallbacks]
class LinkCreateLog extends AggregateRoot
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(length: 128, unique: true)]
        private string  $hash,

        #[ORM\Column(length: 64, nullable: true)]
        private ?string $status,

        #[ORM\Column(length: 255, nullable: true)]
        private ?string $message
    )
    {
    }

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    /**
     * Gets triggered only on insert
     */
    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTime("now");
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
