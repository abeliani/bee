<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Model;

readonly class Tag
{
    public function __construct(private int $id, private string $name, private int $frequency)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFrequency(): int
    {
        return $this->frequency;
    }
}
