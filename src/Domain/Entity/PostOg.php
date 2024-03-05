<?php 

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Entity;

use Abeliani\Blog\Domain\Entity\Og\OgArticle;
use Abeliani\Blog\Domain\Enum;

final readonly class PostOg extends Jsonable
{
    public function __construct(
        private string $title,
        private Enum\OgType $type,
        private string $url,
        private string $description,
        private string $siteName,
        private Enum\Locale $locale,
        private OgArticle $article,
    ) {
    }

    public function toArray(): array
    {
        return array_merge([
            'title' => $this->title,
            'type' => $this->type->value,
            'url' => $this->url,
            'description' => $this->description,
            'siteName' => $this->siteName,
            'locale' => $this->locale->value,
        ], $this->article->toArray());
    }
}
