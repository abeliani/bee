<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Model;

use Abeliani\Blog\Domain\Collection\Concrete;
use Abeliani\Blog\Domain\Entity;
use Abeliani\Blog\Domain\Enum;
use Abeliani\Blog\Domain\Trait\SurrogateId;

class Article
{
    use SurrogateId;

    public function __construct(
        ?int $id,
        private readonly ?int $translateId,
        private readonly int $categoryId,
        private readonly string $title,
        private readonly string $slug,
        private readonly string $preview,
        private readonly string $content,
        private readonly array $tags,
        private readonly Concrete\ImageCollection $images,
        private readonly string $imageAlt,
        private readonly ?string $video,
        private readonly Entity\SeoMeta $seoMeta,
        private readonly Entity\ArticleOg $seoOg,
        private readonly Enum\Language $language,
        private readonly int $createdBy,
        private readonly ?int $editedBy,
        private readonly Enum\ArticleStatus $status,
        private readonly \DateTimeImmutable $createdAt,
        private ?\DateTimeImmutable $publishedAt = null,
        private readonly ?\DateTimeImmutable $updatedAt = null,
        private readonly int $view_count = 0,
    ) {
        if ($id) {
            $this->id = $id;
        }
        if (!$publishedAt) {
            $this->publishedAt = $createdAt;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function getTranslateId(): int
    {
        return $this->translateId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getPreview(): string
    {
        return $this->preview;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getImages(): Concrete\ImageCollection
    {
        return $this->images;
    }

    public function getImageAlt(): string
    {
        return $this->imageAlt;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function getSeoMeta(): Entity\SeoMeta
    {
        return $this->seoMeta;
    }

    public function getSeoOg(): Entity\ArticleOg
    {
        return $this->seoOg;
    }

    public function getLanguage(): Enum\Language
    {
        return $this->language;
    }

    public function getCreatedBy(): int
    {
        return $this->createdBy;
    }

    public function getEditedBy(): ?int
    {
        return $this->editedBy;
    }

    public function getStatus(): Enum\ArticleStatus
    {
        return $this->status;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getViewCount(): int
    {
        return $this->view_count;
    }
}
