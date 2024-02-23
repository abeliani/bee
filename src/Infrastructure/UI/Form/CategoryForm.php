<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Form;

use Symfony\Component\Validator\Constraints as Assert;

final class CategoryForm
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
    private ?string $status;
    private string $content;
    private string $published_at;

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

    public function getStatus(): string
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
}
