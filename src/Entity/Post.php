<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $addDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PostLike", mappedBy="post", orphanRemoval=true)
     */
    private $postLikes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post", orphanRemoval=true)
     */
    private $comments;

    public function __construct()
    {
        $this->postLikes = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAddDate(): ?\DateTimeInterface
    {
        return $this->addDate;
    }

    /**
     * @return Collection|PostLike[]
     */
    public function getPostLikes(): Collection
    {
        return $this->postLikes;
    }

    public function getLikesCount(): int
    {
        return count($this->getPostLikes());
    }

    public function isLikedBy(User $user)
    {
        return $this->postLikes->exists(function (int $index, PostLike $like) use ($user) {
            return $like->getUser()->getId() === $user->getId();
        });
    }

    public function removePostLike(PostLike $postLike): self
    {
        if ($this->postLikes->contains($postLike)) {
            $this->postLikes->removeElement($postLike);
            // set the owning side to null (unless already changed)
            if ($postLike->getPost() === $this) {
                $postLike->setPost(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }
}
