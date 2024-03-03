<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Repository;

use Abeliani\Blog\Domain\Model\User;
use Abeliani\Blog\Domain\Exception\DomainException;
use Abeliani\Blog\Domain\Repository\User\CreateUserRepositoryInterface;

readonly class CreateUserRepository implements CreateUserRepositoryInterface
{
    protected const TABLE = 'users';

    public function __construct(private \PDO $pdo)
    {
    }

    /**
     * @throws DomainException
     */
    public function create(User $user): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (name,email,password,role,status) VALUES (:n,:e,:p,:r,:s)');
        $stmt->bindValue(':n', $user->getName());
        $stmt->bindValue(':e', $user->getEmail());
        $stmt->bindValue(':p', $user->getPassword());
        $stmt->bindValue(':r', $user->getRole()->value, \PDO::PARAM_INT);
        $stmt->bindValue(':s', $user->getStatus()->value, \PDO::PARAM_INT);

        $result = $stmt->execute();
        $user->setId((int) $this->pdo->lastInsertId());

        return $result;
    }
}
