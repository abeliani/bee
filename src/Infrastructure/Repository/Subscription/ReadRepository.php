<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Repository\Subscription;

use Abeliani\Blog\Domain\Enum\SubscriptionStatus;
use Abeliani\Blog\Domain\Model\Subscription;
use Abeliani\Blog\Domain\Repository\Subscription\ReadRepositoryInterface;

readonly class ReadRepository implements ReadRepositoryInterface
{
    public function __construct(private \PDO $pdo)
    {
    }

    public function findByEmail(string $email): ?Subscription
    {
        $stmt = $this->pdo->prepare('SELECT * FROM subscriptions WHERE email=? LIMIT 1');
        $stmt->execute([$email]);

        if (!$row = $stmt->fetch()) {
            return null;
        }

        return new Subscription(
            $row['email'],
            SubscriptionStatus::from((int) $row['status']),
            new \DateTimeImmutable($row['created_at']),
            $row['updated_at'] ? new \DateTimeImmutable($row['created_at']) : null,
        );
    }
}
