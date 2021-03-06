<?php

namespace App\Entity;

use App\Service\HashtagManager\Hashtaggable;
use App\Service\TextFormatter\Formattable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, Hashtaggable, Formattable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $registerDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notifications", mappedBy="user", orphanRemoval=true)
     */
    private $notifications;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $aboutMe;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Hashtag", mappedBy="users")
     */
    private $hashtags;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->notifications = new ArrayCollection();
        $this->hashtags = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     * @return User
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getRegisterDate(): ?\DateTimeInterface
    {
        return $this->registerDate;
    }

    /**
     * @return Collection|Notifications[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    /**
     * @param Notifications $notification
     * @return User
     */
    public function addNotification(Notifications $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setUser($this);
        }

        return $this;
    }

    /**
     * @param Notifications $notification
     * @return User
     */
    public function removeNotification(Notifications $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAboutMe(): ?string
    {
        return $this->aboutMe;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->getAboutMe();
    }

    public function setContent(?string $content)
    {
        $this->aboutMe = $content;
    }

    /**
     * @param string|null $aboutMe
     * @return User
     */
    public function setAboutMe(?string $aboutMe): self
    {
        $this->aboutMe = $aboutMe;

        return $this;
    }

    /**
     * @return Collection|Hashtag[]
     */
    public function getHashtags(): Collection
    {
        return $this->hashtags;
    }

    /**
     * @param Hashtag $hashtag
     * @return User
     */
    public function addHashtag(Hashtag $hashtag): self
    {
        if (!$this->hashtags->contains($hashtag)) {
            $this->hashtags[] = $hashtag;
            $hashtag->addUser($this);
        }

        return $this;
    }

    /**
     * @param Hashtag $hashtag
     * @return User
     */
    public function removeHashtag(Hashtag $hashtag): self
    {
        if ($this->hashtags->contains($hashtag)) {
            $this->hashtags->removeElement($hashtag);
            $hashtag->removeUser($this);
        }

        return $this;
    }

    public function clearHashtags()
    {
        $this->hashtags->clear();
    }
}
