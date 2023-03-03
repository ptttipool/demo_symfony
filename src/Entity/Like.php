<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use function Symfony\Component\String\u;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LikeRepository")
 * @ORM\Entity
 * @ORM\Table(name="likes",
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="post_like",
 *            columns={"post_id", "user_id"})
 *    }
 * )
 */
class Like
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="likes")
     * @ORM\JoinColumn(nullable=false)
     */
    private Post $post;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function __construct(User $user, Post $post)
    {
        $this->user = $user;
        $this->post = $post;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
