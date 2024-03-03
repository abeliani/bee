<?php

namespace Abeliani\Blog\Infrastructure\Repository;

use Abeliani\Blog\Domain\Enum\UserStatus;
use Abeliani\Blog\Domain\Model\User;
use Abeliani\Blog\Domain\Repository\User\ReadUserRepositoryInterface;
use Abeliani\Blog\Infrastructure\Persistence\Mapper\UserMapper;

readonly class ReadUserRepository implements ReadUserRepositoryInterface
{
    protected const TABLE = 'users';

    public function __construct(private \PDO $pdo, private UserMapper $mapper)
    {
    }

    /**
     * @throws \Exception
     */
    public function find(int $id): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :i AND status = :s LIMIT 1');
        $stmt->bindValue(':i', $id, \PDO::PARAM_INT);
        $stmt->bindValue(':s', UserStatus::Active->value, \PDO::PARAM_INT);
        $stmt->execute();

        return ($row = $stmt->fetch()) ? $this->mapper->map($row) : null;
    }

    /**
     * @throws \Exception
     */
    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :e AND status = :s LIMIT 1');
        $stmt->bindValue(':e', $email);
        $stmt->bindValue(':s', UserStatus::Active->value, \PDO::PARAM_INT);
        $stmt->execute();

        return ($row = $stmt->fetch()) ? $this->mapper->map($row) : null;
    }
}
