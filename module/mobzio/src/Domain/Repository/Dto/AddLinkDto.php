<?php

namespace Mobzio\Domain\Repository\Dto;

final class AddLinkDto
{
    public function __construct(
        // Ссылка которая сокращается
        public string  $web,
        // Шорткод ссылки (например, link1)
        public string  $shortcode,
        // type = custom (выбрать тип ссылки, если вы делаете для wildberries то указать wildberries)
        public ?string $type = 'custom',
        // agree = 2 (описание из доков)
        public ?int    $agree = 2
    )
    {
    }
}
