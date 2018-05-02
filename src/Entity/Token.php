<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TokenRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Token
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $validated = false;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, length=255)
     */
    private $token_key;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $time_limit = -1;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn()
     */
    private $user;

    /**
     * Getting the id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    /**
     * @ORM\PrePersist
     * @return Token
     */
    public function setCreatedAt(): Token
    {
        $this->created_at = new \DateTime();
        return $this;
    }

    /**
     * @return bool
     */
    public function isValidated(): bool
    {
        return $this->validated;
    }

    /**
     * @param bool $validated
     * @return Token
     */
    public function setValidated(bool $validated): Token
    {
        $this->validated = $validated;
        return $this;
    }

    /**
     * @return string
     */
    public function getTokenKey(): string
    {
        return $this->token_key;
    }

    /**
     * @ORM\PrePersist
     * @return Token
     * @throws \Exception
     */
    public function setTokenKey(): Token
    {
        $this->token_key = bin2hex(random_bytes(78));
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeLimit(): int
    {
        return $this->time_limit;
    }

    /**
     * @param int $time_limit
     * @return Token
     */
    public function setTimeLimit(int $time_limit): Token
    {
        $this->time_limit = $time_limit;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Token
     */
    public function setUser(User $user): Token
    {
        $this->user = $user;
        return $this;
    }
}
