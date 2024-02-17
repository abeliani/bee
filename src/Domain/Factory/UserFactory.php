<?php

declare(strict_types=1);


namespace Abeliani\Blog\Domain\Factory;

use Abeliani\Blog\Domain\Enum\Role;
use Abeliani\Blog\Domain\Enum\UserStatus;
use Abeliani\Blog\Domain\Exception\UserException;
use Abeliani\Blog\Domain\Model\User;
use Abeliani\Blog\Domain\Service\PasswordHasher\PasswordHasherInterface;

final readonly class UserFactory
{
    public function __construct(private PasswordHasherInterface $passwordHasher)
    {
    }

    /**
     * @throws UserException
     */
    public function create(
        string $name,
        string $email,
        string $password,
        Role $role,
        ?UserStatus $status = null,
    ): User {
        if (empty($name)) {
            throw new UserException('Name cannot be empty');
        }
        if (empty($email)) {
            throw new UserException('Email cannot be empty');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new UserException('Email must be a valid email');
        }
        if (empty($password)) {
            throw new UserException('Password cannot be empty');
        }
        if ($name === $email) {
            throw new UserException('Email cannot be the same as name');
        }
        if ($status === null) {
            $status = UserStatus::InActive;
        }

        return new User(
            null,
            $name,
            $email,
            $this->passwordHasher->hash($password),
            $role,
            $status
        );
    }
}
