<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Repository\Subscription;

use Abeliani\Blog\Domain\Enum\SubscriptionStatus;
use Abeliani\Blog\Domain\Model\Subscription;
use Abeliani\Blog\Domain\Repository\Subscription\CreateRepositoryInterface;

readonly class CreateRepository implements CreateRepositoryInterface
{
    protected const TABLE = 'subscriptions';

    public function __construct(private \PDO $pdo)
    {
    }

    public function save(Subscription $s): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO subscriptions (email, status, updated_at) VALUES (?, ?, ?)');
        // todo add exception if false
        $result = $stmt->execute([$s->getEmail(), $s->getStatus()->value, $s->getUpdatedAt()]);
    }

    public function activate(Subscription $s): ?Subscription
    {
        $stmt = $this->pdo->prepare('UPDATE subscriptions SET status = ?, updated_at = ? WHERE email = ?');
        $status = SubscriptionStatus::Active;
        $updated = new \DateTimeImmutable();

        $result = $stmt->execute([
            $status->value,
            $updated->format('Y-m-d H:i:s'),
            $s->getEmail(),
        ]);

        return new Subscription(
            $s->getEmail(),
            $status,
            $s->getCreatedAt(),
            $updated,
        );
    }
}
