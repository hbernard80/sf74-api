<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Ulid;

#[AsCommand(name: 'app:backfill-user-ulid')]
class BackfillUserUlidCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            if ($user->getUlid() === null) {
                $user->setUlid(new Ulid());
            }
        }

        $this->em->flush();

        $output->writeln('Les ULID manquants ont été générés.');

        return Command::SUCCESS;
    }
}