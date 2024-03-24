<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Entity;

use Abeliani\Blog\Domain\Enum\UrlProtocol;

final readonly class RedirectUrl
{
    public function __construct(
        private string $hash,
        private string $path,
        private UrlProtocol $protocol,
        private bool $fast,
        private int $viewCount = 0,
        private ?\DateTimeImmutable $createdAt = null
    ) {
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getProtocol(): UrlProtocol
    {
        return $this->protocol;
    }

    public function isFast(): bool
    {
        return $this->fast;
    }

    public function getViewCount(): int
    {
        return $this->viewCount;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
