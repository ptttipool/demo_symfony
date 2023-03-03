<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Post;
use App\Entity\User;
use App\Exception\UserIsNotLikedPostYetException;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;

class PostUnLiker
{
    private EntityManagerInterface $entityManager;

    private LikeRepository $likeRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        LikeRepository $likeRepository
    ) {
        $this->entityManager = $entityManager;
        $this->likeRepository = $likeRepository;
    }

    /**
     * @throws UserIsNotLikedPostYetException
     */
    public function dislikePost(Post $post, User $user): void
    {
        $like = $this->likeRepository->findOneByPostAndUser($post, $user);

        if ($like === null) {
            throw new UserIsNotLikedPostYetException($post, $user);
        }

        $post->removeLike($like);
        $this->entityManager->remove($like);

        $this->entityManager->flush();
    }
}
