<?php

namespace App\Entity;

use App\Repository\AnswerUserSessionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnswerUserSessionRepository::class)
 */
class AnswerUserSession
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="answerUserSessions")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=AtelierSession::class, inversedBy="answerUserSessions")
     */
    private $session;

    /**
     * @ORM\Column(type="boolean")
     */
    private $presence;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSession(): ?AtelierSession
    {
        return $this->session;
    }

    public function setSession(?AtelierSession $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getPresence(): ?bool
    {
        return $this->presence;
    }

    public function setPresence(bool $presence): self
    {
        $this->presence = $presence;

        return $this;
    }
}
