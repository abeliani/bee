<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Model;

use Abeliani\Blog\Domain\Enum;
use Abeliani\Blog\Domain\Trait\SurrogateId;

class Subscription
{
    public function __construct(
        private readonly string $email,
        private readonly Enum\SubscriptionStatus $status,
        private readonly \DateTimeImmutable $createdAt,
        private readonly ?\DateTimeImmutable $updatedAt = null,
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getStatus(): Enum\SubscriptionStatus
    {
        return $this->status;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
