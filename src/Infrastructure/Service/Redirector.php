<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service;

use Abeliani\Blog\Domain\Entity\RedirectUrl;
use Abeliani\Blog\Domain\Repository\Redirector\RepositoryInterface;

class Redirector
{
    private const ALGO = 'sha256';
    private const DENY = ['+', '/', '='];
    private const REPLACEMENT = ['-', '_', ''];

    public function __construct(
      private RepositoryInterface $repository,
    ) {
    }

    public function get(string $hash): ?RedirectUrl
    {
        return $this->repository->find($hash);
    }

    public function make(string $url, int $length = 8): string
    {
        return substr(
            str_replace(self::DENY, self::REPLACEMENT, base64_encode(hash(self::ALGO, $url, true))), 0, $length
        );
    }

    public function save(RedirectUrl $redirectUrl): bool
    {
        return $this->repository->create($redirectUrl);
    }
}
