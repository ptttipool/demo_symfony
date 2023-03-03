<?php

declare(strict_types=1);

namespace App\Exception;

use App\Entity\Post;
use App\Entity\User;

class UserHasAlreadyLikedPostException extends \Exception
{
    public function __construct(Post $post, User $user)
    {
        parent::__construct(sprintf('User has already liked postId#%d userId#%d', $post->getId(), $user->getId()));
    }
}
