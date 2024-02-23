<?php
declare(strict_types=1);

use Abeliani\Blog\Application\Service\UserRegistration\UserRegistrationService;
use Abeliani\Blog\Console\Command\RegisterUserCommand;
use DI\Container;

return [
    RegisterUserCommand::class => function(Container $c): RegisterUserCommand {
        return new RegisterUserCommand($c->get(UserRegistrationService::class));
    },
];
