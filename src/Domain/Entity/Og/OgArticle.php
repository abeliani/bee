<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Entity\Og;

use Abeliani\Blog\Domain\Entity\Jsonable;

final readonly class OgArticle extends Jsonable
{
    public function __construct(
        private string $publishedTime,
        private string $modifiedTime,
        private string $expirationTime,
        private string $author,
        private string $section,
        private string $tag,
    ) {
    }

    public function toArray(): array
    {
        return [
            'article:published_time' => $this->publishedTime,
            'article:modified_time' => $this->modifiedTime,
            'article:expiration_time' => $this->expirationTime,
            'article:author' => $this->author,
            'article:section' => $this->section,
            'article:tag' => $this->tag,
        ];
    }
}
