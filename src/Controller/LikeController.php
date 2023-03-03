<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Exception\UserHasAlreadyLikedPostException;
use App\Exception\UserIsNotLikedPostYetException;
use App\Service\PostLiker;
use App\Service\PostUnLiker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class LikeController extends AbstractController
{
    /**
     * @Security("is_granted('ROLE_USER')")
     *
     * @Route("/posts/{postId}/likes", methods={"POST"}, requirements={"postId" = "\d+"}, name="like_post", options={"expose"=true})
     * @ParamConverter("post", options={"mapping": {"postId":"id"}})
     */
    public function likeToPost(Post $post, PostLiker $postLiker): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if ($user == null) {
            throw new \RuntimeException('user cannot be null');
        }

        try {
            $postLiker->markPostAsLiked($post, $user);
        } catch (UserHasAlreadyLikedPostException $e) {
            return $this->json(['message' => 'user has already liked this post'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([], Response::HTTP_CREATED);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     *
     * @Route("/posts/{postId}/likes", methods={"DELETE"}, requirements={"postId" = "\d+"}, name="unlike_post", options={"expose"=true})
     * @ParamConverter("post", options={"mapping": {"postId":"id"}})
     */
    public function unlikePost(Post $post, PostUnLiker $postUnLiker): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if ($user == null) {
            throw new \RuntimeException('user cannot be null');
        }

        try {
            $postUnLiker->dislikePost($post, $user);
        } catch (UserIsNotLikedPostYetException $e) {
            return $this->json(['message' => 'user is not liked this post'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([], Response::HTTP_OK);
    }
}
