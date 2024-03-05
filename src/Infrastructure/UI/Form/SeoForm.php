<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Form;

use Symfony\Component\Validator\Constraints as Assert;

final class SeoForm implements \JsonSerializable
{
    #[Assert\Type('string')]
    private string $title;

    #[Assert\Type('string')]
    private string $description;

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