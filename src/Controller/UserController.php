<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route('/profile', name: 'app_user_profile_show', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function profileShow(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Utilisateur non authentifié.');
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
}