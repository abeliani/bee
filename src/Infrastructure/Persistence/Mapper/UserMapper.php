<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Persistence\Mapper;

use Abeliani\Blog\Domain\Enum\Role;
use Abeliani\Blog\Domain\Enum\UserStatus;
use Abeliani\Blog\Domain\Mapper\AggregateMapperInterface;
use Abeliani\Blog\Domain\Model\User;

final readonly class UserMapper implements AggregateMapperInterface
{
    /**
     * @throws \Exception
     */
    public function map(array $data): User
    {
        return new User(
            $data['id'],
            $data['name'],
            $data['email'],
            $data['password'],
            Role::from($data['role']),
            UserStatus::from($data['status']),
            isset($data['emailVerifiedAt']) ? new \DateTimeImmutable($data['emailVerifiedAt']) : null,
            isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null,
            isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null,
            isset($data['deleted_at']) ? new \DateTimeImmutable($data['deleted_at']) : null,
            isset($data['lastLogin_at']) ? new \DateTimeImmutable($data['lastLogin_at']) : null,
            $data['login_count'] ?? 0
        );
    }

    /**
     * @return array<User>
     * @throws \Exception
     */
    public function mapMany(array $data): array
    {
        return array_map(fn (array $user) => $this->map($user), $data);
    }
}
