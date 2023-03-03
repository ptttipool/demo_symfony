<?php

declare(strict_types=1);

namespace App\Exception;

use App\Entity\Post;
use App\Entity\User;

class UserIsNotLikedPostYetException extends \Exception
{
    public function __construct(Post $post, User $user)
    {
        parent::__construct(sprintf('User cannot like postId#%d userId#%d', $post->getId(), $user->getId()));
    }
}
