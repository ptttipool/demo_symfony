<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Like;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Like::class);
    }

    public function findOneByPostAndUser(Post $post, User $user): ?Like
    {
        return parent::findOneBy(['user' => $user->getId(), 'post' => $post->getId()]);
    }

    public function getPostLikesCount(Post $post): int
    {
        return (int) $this->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->andWhere('l.post = :post')
            ->setParameters([':post' => $post])
            ->getQuery()
            ->getSingleScalarResult();
    }
}
