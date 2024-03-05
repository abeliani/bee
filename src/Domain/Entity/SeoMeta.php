<?php 

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Entity;

final readonly class SeoMeta extends Jsonable
{
    public function __construct(
        private string $title,
        private string $description,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
