<?php
declare(strict_types=1);

namespace Mobzio\Domain\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Aggregate\AggregateRoot;

#[ORM\Entity]
#[ORM\Table('mobzio_link')]
#[ORM\HasLifecycleCallbacks]
class Link extends AggregateRoot
{
    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private DateTime $updatedAt;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: "uuid", unique: true)]
        private string  $linkId,

        #[ORM\Column(length: 255, nullable: true)]
        private ?string $productId,

        #[ORM\Column]
        private int     $mobzioLinkId,

        #[ORM\Column(length: 1000)]
        private string  $link,

        #[ORM\Column(length: 64)]
        private string  $shortcode,

        #[ORM\Column(length: 1000, nullable: true)]
        private ?string  $phrase,

        #[ORM\Column(length: 1000, nullable: true)]
        private ?string $description,

        #[ORM\Column(length: 2000)]
        private string  $originalLink,

        /**
         * @var string $campaign - utm_campaign
         */
        #[ORM\Column(length: 64, nullable: true)]
        private ?string $campaign,

        /**
         * @var string $blogger - utm_content
         */
        #[ORM\Column(length: 64, nullable: true)]
        private ?string $blogger,

        /**
         * @var string $source - utm_source
         */
        #[ORM\Column(length: 64, nullable: true)]
        private ?string $source,

        /**
         * @var string $medium - utm_medium
         */
        #[ORM\Column(length: 64, nullable: true)]
        private ?string $medium,

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $linkType = null,

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $folderId = null,

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $folderName = null,

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $folderDescription = null
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

    public function getLinkId(): string
    {
        return $this->linkId;
    }

    public function setLinkId(string $linkId): void
    {
        $this->linkId = $linkId;
    }

    public function getProductId(): ?string
    {
        return $this->productId;
    }

    public function setProductId(?string $productId): void
    {
        $this->productId = $productId;
    }

    public function getMobzioLinkId(): int
    {
        return $this->mobzioLinkId;
    }

    public function setMobzioLinkId(int $mobzioLinkId): void
    {
        $this->mobzioLinkId = $mobzioLinkId;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    public function getShortcode(): string
    {
        return $this->shortcode;
    }

    public function setShortcode(string $shortcode): void
    {
        $this->shortcode = $shortcode;
    }

    public function getPhrase(): ?string
    {
        return $this->phrase;
    }

    public function setPhrase(?string $phrase): void
    {
        $this->phrase = $phrase;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getOriginalLink(): string
    {
        return $this->originalLink;
    }

    public function setOriginalLink(string $originalLink): void
    {
        $this->originalLink = $originalLink;
    }

    public function getCampaign(): ?string
    {
        return $this->campaign;
    }

    public function setCampaign(?string $campaign): void
    {
        $this->campaign = $campaign;
    }

    public function getBlogger(): ?string
    {
        return $this->blogger;
    }

    public function setBlogger(?string $blogger): void
    {
        $this->blogger = $blogger;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): void
    {
        $this->source = $source;
    }

    public function getMedium(): ?string
    {
        return $this->medium;
    }

    public function setMedium(?string $medium): void
    {
        $this->medium = $medium;
    }

    public function getLinkType(): ?string
    {
        return $this->linkType;
    }

    public function setLinkType(?string $linkType): void
    {
        $this->linkType = $linkType;
    }

    public function getFolderId(): ?string
    {
        return $this->folderId;
    }

    public function setFolderId(?string $folderId): void
    {
        $this->folderId = $folderId;
    }

    public function getFolderName(): ?string
    {
        return $this->folderName;
    }

    public function setFolderName(?string $folderName): void
    {
        $this->folderName = $folderName;
    }

    public function getFolderDescription(): ?string
    {
        return $this->folderDescription;
    }

    public function setFolderDescription(?string $folderDescription): void
    {
        $this->folderDescription = $folderDescription;
    }
}
