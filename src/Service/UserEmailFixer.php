<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserEmailFixer
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function fixAll(): int
    {
        $users = $this->userRepository->findAll();
        $count = 0;

        foreach ($users as $user) {
            $oldEmail = $user->getEmail();

            if (!$oldEmail) {
                continue;
            }

            $newEmail = $this->buildEmailFromUser($user, $oldEmail);

            if ($newEmail !== $oldEmail) {
                $user->setEmail($newEmail);
                $count++;
            }
        }

        $this->entityManager->flush();

        return $count;
    }

    private function buildEmailFromUser(User $user, string $currentEmail): string
    {
        $domain = $this->extractDomain($currentEmail);

        $firstname = $this->slugify($user->getFirstname() ?? '');
        $lastname = $this->slugify($user->getLastname() ?? '');

        return strtolower(sprintf('%s.%s@%s', $firstname, $lastname, $domain));
    }

    private function extractDomain(string $email): string
    {
        $parts = explode('@', $email, 2);

        return $parts[1] ?? 'example.com';
    }

    private function slugify(string $value): string
    {
        $value = trim($value);

        $transliteration = [
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a',
            'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
            'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ñ' => 'n',
            'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o',
            'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u',
            'ý' => 'y', 'ÿ' => 'y',
            'À' => 'a', 'Á' => 'a', 'Â' => 'a', 'Ã' => 'a', 'Ä' => 'a', 'Å' => 'a',
            'Ç' => 'c',
            'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e',
            'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i',
            'Ñ' => 'n',
            'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Õ' => 'o', 'Ö' => 'o',
            'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u',
            'Ý' => 'y',
            '\'' => '',
            '’' => '',
            ' ' => '-',
        ];

        $value = strtr($value, $transliteration);
        $value = preg_replace('/[^a-zA-Z0-9\-]/', '', $value) ?? '';
        $value = preg_replace('/-+/', '-', $value) ?? '';
        $value = trim($value, '-');

        return strtolower($value);
    }
}