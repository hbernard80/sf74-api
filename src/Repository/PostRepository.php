<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
final class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * QueryBuilder utilisé par Pagerfanta
     */
    public function createListQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
          ->leftJoin('p.category', 'c')->addSelect('c')
          ->leftJoin('p.author', 'a')->addSelect('a')
          ->orderBy('p.id', 'DESC');
    }
}