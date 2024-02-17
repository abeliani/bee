<?php
declare(strict_types=1);

namespace Abeliani\Blog\Domain\Model;

use Abeliani\Blog\Domain\Enum\Role;
use Abeliani\Blog\Domain\Enum\UserStatus;
use Abeliani\Blog\Domain\Trait\SurrogateId;

class User
{
    use SurrogateId;

    public function __construct(
        ?int $id,
        private readonly string $name,
        private readonly string $email,
        private readonly string $password,
        private readonly Role $role,
        private readonly UserStatus $status,
        private readonly ?\DateTimeImmutable $emailVerifiedAt = null,
        private readonly ?\DateTimeImmutable $createdAt = null,
        private readonly ?\DateTimeImmutable $updatedAt = null,
        private readonly ?\DateTimeImmutable $deletedAt = null,
        private readonly ?\DateTimeImmutable $lastLoginAt = null,
        private readonly int $loginCount = 0,
    ) {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getStatus(): UserStatus
    {
        return $this->status;
    }

    public function getEmailVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->emailVerifiedAt;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function getLastLoginAt(): ?\DateTimeImmutable
    {
        return $this->lastLoginAt;
    }

    public function getLoginCount(): int
    {
        return $this->loginCount;
    }
}
