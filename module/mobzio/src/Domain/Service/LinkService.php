<?php

namespace Mobzio\Domain\Service;

use Mobzio\Domain\Repository\LinkRepositoryInterface;

readonly class LinkService
{
    public function __construct(
        public LinkRepositoryInterface $linkRepository
    )
    {
    }

    /**
     * Разбирает wildberries original_link на части и возвращает Dto
     * @param string $link original_link
     * @return OriginalLinkPartsDto
     */
    public function getOriginalLinkParts(string $link): OriginalLinkPartsDto
    {
        $partsDto = new OriginalLinkPartsDto();

        $parts = preg_split('/[;\/&=?]/', $link);
        foreach ($parts as $key => $value) {
            switch ($value) {
                case 'catalog':
                    $partsDto->nmId = (int)$parts[++$key];
                    break;
                case 'search':
                    $partsDto->phrase = urldecode($parts[++$key]);
                    break;
                case 'utm_source':
                    $partsDto->source = $parts[++$key];
                    break;
                case 'utm_medium':
                    $partsDto->medium = $parts[++$key];
                    break;
                case 'utm_campaign':
                    $partsDto->campaign = $parts[++$key];
                    break;
                case 'utm_content':
                    $partsDto->blogger = $parts[++$key];
                    break;
            }
        }

        return $partsDto;
    }

    /**
     * @return string
     */
    public function getSortCode(): string
    {
        $shortcode = $this->createShortCode();

        if ($this->linkRepository->findOneBy(['shortcode' => $shortcode])) {
            $this->getSortCode();
        }

        return $shortcode;
    }

    /**
     * @return string
     */
    private function createShortCode(): string
    {
        $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $shortCode = [];
        $shortCodeLength = rand(3, 10);
        $alphaLength = strlen($string) - 1;

        for ($i = 0; $i < $shortCodeLength; $i++) {
            $n = rand(0, $alphaLength);
            $shortCode[] = $string[$n];
        }

        return implode($shortCode);
    }


}
