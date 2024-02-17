<?php 

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Model;

class SeoOg
{
    public function __construct(
        private readonly string $title,
        private readonly string $type,
        private readonly string $url,
        private readonly string $image,
        private readonly string $description,
        private readonly string $siteName,
        private readonly string $locale,
        private readonly string $audio,
        private readonly string $video,
        private readonly string $articlePublishedTime,
        private readonly string $articleModifiedTime,
        private readonly string $articleExpirationTime,
        private readonly string $articleAuthor,
        private readonly string $articleSection,
        private readonly string $articleTag,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSiteName(): string
    {
        return $this->siteName;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getAudio(): string
    {
        return $this->audio;
    }

    public function getVideo(): string
    {
        return $this->video;
    }

    public function getArticlePublishedTime(): string
    {
        return $this->articlePublishedTime;
    }

    public function getArticleModifiedTime(): string
    {
        return $this->articleModifiedTime;
    }

    public function getArticleExpirationTime(): string
    {
        return $this->articleExpirationTime;
    }

    public function getArticleAuthor(): string
    {
        return $this->articleAuthor;
    }

    public function getArticleSection(): string
    {
        return $this->articleSection;
    }

    public function getArticleTag(): string
    {
        return $this->articleTag;
    }
}
