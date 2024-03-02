<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Form;

use Abeliani\Blog\Domain\Enum\CategoryStatus;
use Abeliani\Blog\Domain\Enum\Languages;
use Abeliani\Blog\Domain\Interface\ToArrayInterface;
use Abeliani\Blog\Infrastructure\Service\RequestValidator\Validator\EnumV;
use Symfony\Component\Validator\Constraints as Assert;

final class CategoryForm implements ToArrayInterface
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 100)]
    private string $title;

    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 100)]
    private string $slug;

    #[Assert\Valid]
    private SeoForm $seo;

    #[Assert\Valid]
    private MediaForm $media;

    #[Assert\Length(min: 50)]
    private string $content;

    #[Assert\NotBlank]
    #[EnumV(enumClass: CategoryStatus::class)]
    private CategoryStatus $status;

    private string $published_at;

    #[Assert\NotBlank]
    #[EnumV(enumClass: Languages::class)]
    private string $language;


    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getSeo(): SeoForm
    {
        return $this->seo;
    }

    public function getMedia(): MediaForm
    {
        return $this->media;
    }

    public function getStatus(): CategoryStatus
    {
        return $this->status;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getPublishedAt(): string
    {
        return $this->published_at;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'lang' => $this->language,
            'seo' => $this->seo,
            'media' => $this->media,
            'status' => $this->status,
            'content' => $this->content,
            'published_at' => $this->published_at,
        ];
    }
}
