<?php

namespace Mobzio\Domain\Repository\Dto;

final class AddLinkResponseDto
{
    public function __construct(
        public string  $status,
        public string  $message,
        public ?string $linkId = null,
    )
    {
    }
}
