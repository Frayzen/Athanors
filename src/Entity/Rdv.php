<?php

namespace App\Entity;

use App\Repository\RdvRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RdvRepository::class)
 */
class Rdv
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Professional::class, inversedBy="rdvs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pro;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="rdvs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validate;

    /**
     * @ORM\OneToOne(targetEntity=AskCancel::class, mappedBy="rdv", cascade={"persist", "remove"})
     */
    private $askCancel;

    /**
     * @ORM\Column(type="boolean")
     */
    private $viewed = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPro(): ?Professional
    {
        return $this->pro;
    }

    public function setPro(?Professional $pro): self
    {
        $this->pro = $pro;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getValidate(): ?bool
    {
        return $this->validate;
    }

    public function setValidate(bool $validate): self
    {
        $this->validate = $validate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $start
     */
    public function setStart($start): void
    {
        $this->start = $start;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param mixed $end
     */
    public function setEnd($end): void
    {
        $this->end = $end;
    }

    public function getAskCancel(): ?AskCancel
    {
        return $this->askCancel;
    }

    public function setAskCancel(?AskCancel $askCancel): self
    {
        $this->askCancel = $askCancel;

        // set (or unset) the owning side of the relation if necessary
        $newRdv = null === $askCancel ? null : $this;
        if ($askCancel->getRdv() !== $newRdv) {
            $askCancel->setRdv($newRdv);
        }

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
