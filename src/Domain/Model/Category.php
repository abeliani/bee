<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Model;

use Abeliani\Blog\Domain\Trait\SurrogateId;

class Category
{
    use SurrogateId;

    public function __construct(
        ?int $id,
        private readonly string $title,
        private readonly string $slug, 
        private readonly string $content,
        private readonly array $images,
        private readonly string $imageAlt,
        private readonly ?string $video,
        private readonly array $seoMeta,
        private readonly array $seoOg,
        private readonly string $language,
        private readonly int $createdBy,
        private readonly int $status,
        private readonly \DateTimeImmutable $createdAt,
        private ?\DateTimeImmutable $publishedAt = null,
        private readonly ?\DateTimeImmutable $updatedAt = null,
        private readonly ?\DateTimeImmutable $deletedAt = null,
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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getImages(): array
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

    public function getSeoMeta(): array
    {
        return $this->seoMeta;
    }

    public function getSeoOg(): array
    {
        return $this->seoOg;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getCreatedBy(): int
    {
        return $this->createdBy;
    }

    public function getStatus(): int
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

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }
}
