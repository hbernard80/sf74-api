<?php

// Commande d'exécution : 

namespace App\Command;

use App\Service\UserEmailFixer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fix-user-emails',
    description: 'Corrige les emails des utilisateurs en firstname.lastname@domaine',
)]
class FixUserEmailsCommand extends Command
{
    public function __construct(private UserEmailFixer $userEmailFixer) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $io = new SymfonyStyle($input, $output);

        $count = $this->userEmailFixer->fixAll();

        $io->success(sprintf('%d email(s) corrigé(s) en base.', $count));

        return Command::SUCCESS;
    }
}