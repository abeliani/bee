<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Model;

use Abeliani\Blog\Domain\Enum\CategoryStatus;
use Abeliani\Blog\Domain\Exception\CategoryException;

final class Category
{
    public function __construct(
        private readonly string $title,
        private readonly string $slug, 
        private readonly string $content,
        private readonly array $images,
        private readonly array $seoMeta,
        private readonly array $seoOg,
        private readonly CategoryStatus $status,
        private readonly \DateTimeImmutable $createdAt,
        private readonly ?\DateTimeImmutable $updatedAt = null,
        private readonly ?\DateTimeImmutable $deletedAt = null,
        private ?int $id = null,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @throws CategoryException
     */
    public function setId(int $id): void
    {
        if ($this->id !== null) {
            throw new CategoryException("ID is already set.");
        }

        $this->id = $id;
    }
}
