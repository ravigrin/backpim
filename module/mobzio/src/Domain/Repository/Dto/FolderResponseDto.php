<?php

namespace Mobzio\Domain\Repository\Dto;

final class FolderResponseDto
{
    public function __construct(
        public ?string $linkType = null,
        public ?string $folderId = null,
        public ?string $folderName = null,
        public ?string $folderDescription = null
    )
    {
    }
}
