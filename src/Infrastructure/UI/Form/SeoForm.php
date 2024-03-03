<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Form;

final class SeoForm implements \JsonSerializable
{
    private string $title, $description;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function jsonSerialize(): array
    {
        return [
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
        ];
    }
}