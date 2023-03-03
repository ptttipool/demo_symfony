<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Like;
use App\Entity\Post;
use App\Entity\User;
use App\Exception\UserHasAlreadyLikedPostException;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;

class PostLiker
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
     * @throws UserHasAlreadyLikedPostException
     */
    public function markPostAsLiked(Post $post, User $user): void
    {
        if (null !== $this->likeRepository->findOneByPostAndUser($post, $user)) {
            throw new UserHasAlreadyLikedPostException($post, $user);
        }

        $like = new Like($user, $post);
        $post->addLike($like);

        $this->entityManager->persist($like);
        $this->entityManager->flush();
    }
}
