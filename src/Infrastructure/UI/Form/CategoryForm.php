<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Form;

use Abeliani\Blog\Domain\Enum;
use Abeliani\Blog\Domain\Interface\ToArrayInterface;
use Abeliani\Blog\Infrastructure\Service\RequestValidator\Validator\EnumV;
use Symfony\Component\Validator\Constraints as Assert;

final class CategoryForm implements ToArrayInterface
{
    #[Assert\Type('integer')]
    private int $id;

    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 100)]
    private string $title;

    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 100)]
    private string $slug;

    #[Assert\Valid]
    private SeoForm $seo;

    #[Assert\Valid]
    private OgForm $og;

    #[Assert\Valid]
    private MediaForm $media;

    #[Assert\Length(min: 50)]
    private string $content;

    #[Assert\NotBlank]
    #[EnumV(enumClass: Enum\CategoryStatus::class)]
    private Enum\CategoryStatus $status;

    #[Assert\Type('string')]
    private string $published_at;

    #[Assert\NotBlank]
    #[EnumV(enumClass: Enum\Language::class)]
    private Enum\Language $language;

    public function getId(): int
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

    public function getSeo(): SeoForm
    {
        return $this->seo;
    }

    public function getOg(): OgForm
    {
        return $this->og;
    }

    public function getMedia(): MediaForm
    {
        return $this->media;
    }

    public function getStatus(): Enum\CategoryStatus
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

    public function getLanguage(): Enum\Language
    {
        return $this->language;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'lang' => $this->language->value,
            'seo' => $this->seo,
            'media' => $this->media,
            'status' => $this->status->value,
            'content' => $this->content,
            'published_at' => $this->published_at,
        ];
    }
}
