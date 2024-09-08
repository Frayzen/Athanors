<?php

namespace App\Entity;

use App\Repository\AskCancelRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AskCancelRepository::class)
 */
class AskCancel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champs ne peut pas être vide")
     * @Assert\NotNull(message="Ce champs ne peut pas être vide")
     * @Assert\Length(max="255", maxMessage="Ce message ne peut pas contenir plus de 255 caractères")
     */
    private $reason;

    /**
     * @ORM\OneToOne(targetEntity=Rdv::class, inversedBy="askCancel", cascade={"persist", "remove"})
     */
    private $rdv;

    /**
     * @ORM\Column(type="boolean")
     */
    private $viewed;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getRdv(): ?Rdv
    {
        return $this->rdv;
    }

    public function setRdv(?Rdv $rdv): self
    {
        $this->rdv = $rdv;

        return $this;
    }

    public function getViewed(): ?bool
    {
        return $this->viewed;
    }

    public function setViewed(bool $viewed): self
    {
        $this->viewed = $viewed;

        return $this;
    }
}
