<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:user:create',
    description: 'Crée un administrateur',
)]
class CreateUserCommand extends Command
{   
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $hasher)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper("question");
        $io = new SymfonyStyle($input, $output);

        $questions = [
            "email" => [
                "question" => "Email de l'utilisateur: ",
            ],
            "password" => [
                "question" => "Mot de passe: "
            ]
        ];

        foreach( $questions as $key => $question){
            $question = new Question($question["question"]);
            $questions[$key]['value'] = $helper->ask($input, $output, $question);
        }

        $user = $this->userRepository->findOneBy(['email' => $questions["email"]["value"]]) ?? new User();

        $user->setEmail($questions["email"]["value"]);
        $user->setPassword( $this->hasher->hashPassword( $user,  $questions["password"]["value"]));
        $user->setRoles(["ROLE_ADMIN"]);
        
        $this->userRepository->save($user,true);
        

        $io->success('User have been stored in database ('.$user->getId().')');

        return Command::SUCCESS;
    }
}
