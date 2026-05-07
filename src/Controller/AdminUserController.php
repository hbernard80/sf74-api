<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Ulid;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
final class AdminUserController extends AbstractController
{
    #[Route('/users', name: 'app_admin_user_index', methods: ['GET'])]
    public function userIndex(UserRepository $userRepository, Request $request): Response
    {
        $query = $userRepository->findAllOrdered();

        $adapter = new QueryAdapter($query);
        $pager = new Pagerfanta($adapter);

        $currentPage = $request->query->getInt('page', 1);

        $pager->setMaxPerPage(10);
        $pager->setCurrentPage($currentPage);

        return $this->render('admin/users-list.html.twig', [
            'users' => $pager
        ]);
    }

    #[Route(
        '/user/show/{ulid}',
        name: 'app_admin_user_show',
        methods: ['GET'],
        requirements: ['ulid' => '[0-7][0-9A-HJKMNP-TV-Z]{25}']
    )]
    public function userShow(
        Ulid $ulid,
        #[MapEntity(mapping: ['ulid' => 'ulid'])] User $user
    ): Response {
        return $this->render('admin/user-show.html.twig', [
            'user' => $user,
        ]);
    }
}
