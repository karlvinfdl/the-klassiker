<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
  name: 'app:create-admin',
  description: 'Creates a new administrator user',
)]
class CreateAdminUserCommand extends Command
{
  private EntityManagerInterface $entityManager;
  private UserPasswordHasherInterface $passwordHasher;

  public function __construct(
    EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $passwordHasher
  ) {
    parent::__construct();
    $this->entityManager = $entityManager;
    $this->passwordHasher = $passwordHasher;
  }

  protected function configure(): void
  {
    $this
      ->addOption('email', 'e', InputOption::VALUE_OPTIONAL, 'The email of the new user')
      ->addOption('password', 'p', InputOption::VALUE_OPTIONAL, 'The password of the new user');
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $helper = $this->getHelper('question');

    // Get email
    $email = $input->getOption('email');
    if (!$email) {
      $question = new Question('Please enter the email of the new user: ');
      $email = $helper->ask($input, $output, $question);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $output->writeln('<error>Invalid email format.</error>');
      return Command::FAILURE;
    }

    // Get password
    $password = $input->getOption('password');
    if (!$password) {
      $question = new Question('Please enter the password of the new user: ');
      $question->setHidden(true);
      $question->setHiddenFallback(false);
      $password = $helper->ask($input, $output, $question);
    }

    if (strlen($password) < 8) {
      $output->writeln('<error>Password must be at least 8 characters.</error>');
      return Command::FAILURE;
    }

    // Create user
    $user = new User();
    $user->setEmail($email);
    $user->setRoles(['ROLE_ADMIN']);
    $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
    $user->setPassword($hashedPassword);

    $this->entityManager->persist($user);
    $this->entityManager->flush();

    $output->writeln(sprintf('<info>Successfully created admin user: %s</info>', $email));

    return Command::SUCCESS;
  }
}

