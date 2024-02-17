<?php 

declare(strict_types=1);

use Abeliani\Blog\Domain\Model\Jsonable;

class SeoMeta extends Jsonable
{
    public function __construct(
        private readonly string $title,
        private readonly string $description,
        private readonly string $keywords,
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

    public function getKeywords(): string
    {
        return $this->keywords;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'keywords' => $this->keywords,
        ];
    }
}
