<?php
declare(strict_types=1);

namespace Abeliani\Blog\Console\Command;

use Abeliani\Blog\Application\Service\UserRegistration\UserRegistrationService;
use Abeliani\Blog\Domain\Enum\Role;
use Abeliani\Blog\Domain\Enum\UserStatus;
use Abeliani\Blog\Domain\Exception\UserException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

final class RegisterUserCommand extends Command
{
    public function __construct(private readonly UserRegistrationService $registrationService)
    {
        parent::__construct('app:user-create-root');
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Registers a new root user.')
            ->setHelp('This command allows you to register a root user...');
    }

    /**
     * @throws UserException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $name = $helper->ask($input, $output, new Question('Name: '));
        $email = $helper->ask($input, $output, new Question('Email: '));
        $question = (new Question('Password: '))
            ->setHidden(true)
            ->setHiddenFallback(false);
        $password = $helper->ask($input, $output, $question);

        try {
            $this->registrationService->register($name, $email, $password,  Role::Admin, UserStatus::Active);
            $output->writeln('User added successfully');
        } catch (UserException $e) {
            $output->writeln($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}