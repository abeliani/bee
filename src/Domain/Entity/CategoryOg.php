<?php 

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Entity;

use Abeliani\Blog\Domain\Enum;

final readonly class CategoryOg extends Jsonable
{
    public function __construct(
        private string $title,
        private Enum\OgType $type,
        private string $url,
        private string $description,
        private string $site_name,
        private Enum\Locale $locale,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getType(): Enum\OgType
    {
        return $this->type;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSiteName(): string
    {
        return $this->site_name;
    }

    public function getLocale(): Enum\Locale
    {
        return $this->locale;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'type' => $this->type->value,
            'url' => $this->url,
            'description' => $this->description,
            'site_name' => $this->site_name,
            'locale' => $this->locale->value,
        ];
    }
}
