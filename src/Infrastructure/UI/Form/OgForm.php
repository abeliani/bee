<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Form;

use Abeliani\Blog\Domain\Enum\Locale;
use Symfony\Component\Validator\Constraints as Assert;
use Abeliani\Blog\Infrastructure\Service\RequestValidator\Validator\EnumV;

class OgForm implements \JsonSerializable
{
    #[Assert\Type('string')]
    private string $title;
    #[Assert\Type('string')]
    private string $type;

    #[Assert\Url]
    private string $url;

    #[Assert\Type('string')]
    private string $description;

    #[Assert\Type('string')]
    private string $site_name;

    #[EnumV(enumClass: Locale::class)]
    private Locale $locale;

    public function jsonSerialize(): array
    {
        return [
            'title' => $this->title,
            'type' => $this->type,
            'url' => $this->url,
            'description' => $this->description,
            'site_name' => $this->site_name,
            'locale' => $this->locale->value,
        ];
    }
}