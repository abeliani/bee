<?php

namespace Abeliani\Blog\Application\Service;

use Abeliani\Blog\Domain\Enum\Role;
use Abeliani\Blog\Domain\Enum\UserStatus;
use Abeliani\Blog\Domain\Exception\UserException;
use Abeliani\Blog\Domain\Factory\UserFactory;
use Abeliani\Blog\Domain\Model\User;
use Abeliani\Blog\Domain\Repository\User\CreateUserRepositoryInterface;

readonly class UserRegistrationService
{
    public function __construct(
        private CreateUserRepositoryInterface $repository,
        private UserFactory $userFactory,
    ) {
    }

    /**
     * @throws UserException
     */
    public function register(string $name, string $email, string $password, Role $role, UserStatus $status): User
    {
        $user = $this->userFactory->create($name, $email, $password, $role, $status);
        $this->repository->create($user);

        return $user;
    }
}
