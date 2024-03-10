<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Model;

use Abeliani\Blog\Domain\Collection\Concrete;
use Abeliani\Blog\Domain\Entity;
use Abeliani\Blog\Domain\Enum;
use Abeliani\Blog\Domain\Trait\SurrogateId;

class Category
{
    use SurrogateId;

    public function __construct(
        ?int $id,
        private readonly string $title,
        private readonly string $slug, 
        private readonly string $content,
        private readonly Concrete\ImageCollection $images,
        private readonly string $imageAlt,
        private readonly Entity\SeoMeta $seoMeta,
        private readonly Entity\CategoryOg $seoOg,
        private readonly Enum\Language $language,
        private readonly int $createdBy,
        private readonly ?int $editedBy,
        private readonly Enum\CategoryStatus $status,
        private readonly \DateTimeImmutable $createdAt,
        private readonly ?\DateTimeImmutable $updatedAt = null,
    ) {
        if ($id) {
            $this->id = $id;
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

    public function getImages(): Concrete\ImageCollection
    {
        return $this->images;
    }

    public function getImageAlt(): string
    {
        return $this->imageAlt;
    }

    public function getSeoMeta(): Entity\SeoMeta
    {
        return $this->seoMeta;
    }

    public function getSeoOg(): Entity\CategoryOg
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

    public function getStatus(): Enum\CategoryStatus
    {
        return $this->status;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
