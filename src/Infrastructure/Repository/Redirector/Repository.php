<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Repository\Redirector;

use Abeliani\Blog\Domain\Entity\RedirectUrl;
use Abeliani\Blog\Domain\Enum\UrlProtocol;
use Abeliani\Blog\Domain\Repository\Redirector\RepositoryInterface;

class Repository implements RepositoryInterface
{
    public function __construct(private \PDO $pdo)
    {
    }

    public function create(RedirectUrl $url): bool
    {
        return $this->pdo
            ->prepare('INSERT IGNORE INTO redirector (hash, path, protocol, fast) VALUES (?, ?, ?, ?)')
            ->execute([$url->getHash(), $url->getPath(), $url->getProtocol()->value, (int) $url->isFast()]);
    }

    public function find(string $hash): ?RedirectUrl
    {
        $sql = 'SELECT hash, path, protocol, fast, view_count, created_at FROM redirector WHERE hash = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$hash]);

        if (!$item = $stmt->fetch()) {
            return null;
        }

        return new RedirectUrl(
            $item['hash'],
            $item['path'],
            UrlProtocol::from((int) $item['protocol']),
            (bool) $item['fast'],
            (int) $item['view_count'],
            new \DateTimeImmutable($item['created_at']),
        );
    }
}