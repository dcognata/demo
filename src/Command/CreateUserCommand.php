<?php

namespace App\Command;

use App\Domain\Auth\UserTools;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:user:create';
    protected static string $defaultDescription = 'Create a new user';
    private UserTools $createUserService;

    public function __construct(UserTools $createUserService)
    {
        $this->createUserService = $createUserService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');

        $question = new Question('Please enter an username' . PHP_EOL);
        $username = $helper->ask($input, $output, $question);

        $question = new Question('Please enter a password' . PHP_EOL);
        $question->setHidden(true);
        $password = $helper->ask($input, $output, $question);

        if (($ret = $this->createUserService->create($username, $password)) === true) {
            $io->success('new user : ' . $username . ' correctly created and ready to use on app.');
            return Command::SUCCESS;
        }

        $io->info($ret);
        return Command::FAILURE;
    }
}
