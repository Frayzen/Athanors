<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Cette adresse mail est déjà lié à un compte")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Tout les champs ne sont pas coomplets")
     */
    private $tel;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Tout les champs ne sont pas coomplets")
     */
    private $firstName;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Tout les champs ne sont pas coomplets")
     */
    private $lastName;
    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(message="L'adresse mail est invalide")
     * @Assert\NotBlank(message="Tout les champs ne sont pas coomplets")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Tout les champs ne sont pas coomplets")
     */
    private $password;

    /**
     * @ORM\OneToOne(targetEntity=Professional::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $pro;

    /**
     * @ORM\OneToMany(targetEntity=Rdv::class, mappedBy="client")
     */
    private $rdvs;

    /**
     * @ORM\Column(type="boolean")
     */
    private $regular;

    /**
     * @ORM\OneToMany(targetEntity=CancelledRdv::class, mappedBy="user")
     */
    private $cancelledRdvs;

    /**
     * @ORM\ManyToMany(targetEntity=Atelier::class, mappedBy="members")
     */
    private $ateliers;
    /**
     * @ORM\OneToMany(targetEntity=AnswerUserSession::class, mappedBy="user")
     */
    private $answerUserSessions;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $activation_id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activated;

    public function __construct()
    {
        $this->rdvs = new ArrayCollection();
        $this->cancelledRdvs = new ArrayCollection();
        $this->ateliers = new ArrayCollection();
        $this->answerUserSessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        if ($this->pro != null){
            $roles[] = 'ROLE_PRO';
            if ($this->getPro()->getUseAgenda())
                $roles[] = 'ROLE_PRO_AGENDA';
            if ($this->getPro()->getCanManageAtelier())
                $roles[] = 'ROLE_PRO_ATELIER';
            if ($this->getPro()->getCanManageSupervisors())
                $roles[] = 'ROLE_PRO_SUPERVISOR';
        }

        return array_unique($roles);
    }

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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param mixed $tel
     */
    public function setTel($tel): void
    {
        $this->tel = $tel;
    }

    /**
     * @return mixed
     */
    public function getRepeatPassword()
    {
        return $this->repeatPassword;
    }




    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
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
    }

    public function getPro(): ?Professional
    {
        return $this->pro;
    }

    public function setPro(Professional $pro): self
    {
        $this->pro = $pro;

        // set the owning side of the relation if necessary
        if ($pro->getUser() !== $this) {
            $pro->setUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Rdv[]
     */
    public function getRdvs(): Collection
    {
        return $this->rdvs;
    }

    public function addRdv(Rdv $rdv): self
    {
        if (!$this->rdvs->contains($rdv)) {
            $this->rdvs[] = $rdv;
            $rdv->setClient($this);
        }

        return $this;
    }

    public function removeRdv(Rdv $rdv): self
    {
        if ($this->rdvs->contains($rdv)) {
            $this->rdvs->removeElement($rdv);
            // set the owning side to null (unless already changed)
            if ($rdv->getClient() === $this) {
                $rdv->setClient(null);
            }
        }

        return $this;
    }

    public function getFullName(){
        return $this->firstName." ".$this->lastName;
    }

    public function getRegular(): ?bool
    {
        return $this->regular;
    }

    public function setRegular(bool $regular): self
    {
        $this->regular = $regular;

        return $this;
    }

    /**
     * @return Collection|CancelledRdv[]
     */
    public function getCancelledRdvs(): Collection
    {
        return $this->cancelledRdvs;
    }

    public function addCancelledRdv(CancelledRdv $cancelledRdv): self
    {
        if (!$this->cancelledRdvs->contains($cancelledRdv)) {
            $this->cancelledRdvs[] = $cancelledRdv;
            $cancelledRdv->setUser($this);
        }

        return $this;
    }

    public function removeCancelledRdv(CancelledRdv $cancelledRdv): self
    {
        if ($this->cancelledRdvs->contains($cancelledRdv)) {
            $this->cancelledRdvs->removeElement($cancelledRdv);
            // set the owning side to null (unless already changed)
            if ($cancelledRdv->getUser() === $this) {
                $cancelledRdv->setUser(null);
            }
        }

        return $this;
    }
    public function cancelledRdvsNotViewed(){
        $a = [];
        foreach ($this->getCancelledRdvs() as $rdv) {
            if (!$rdv->getViewed())
                $a[] = $rdv;
        }
        return $a;
    }

    /**
     * @return Collection|Atelier[]
     */
    public function getAteliers(): Collection
    {
        return $this->ateliers;
    }

    public function addAtelier(Atelier $atelier): self
    {
        if (!$this->ateliers->contains($atelier)) {
            $this->ateliers[] = $atelier;
            $atelier->addMember($this);
        }

        return $this;
    }

    public function removeAtelier(Atelier $atelier): self
    {
        if ($this->ateliers->removeElement($atelier)) {
            $atelier->removeMember($this);
        }

        return $this;
    }

    /**
    /**
     * @return Collection|AnswerUserSession[]
     */
    public function getAnswerUserSessions(): Collection
    {
        return $this->answerUserSessions;
    }

    public function addAnswerUserSession(AnswerUserSession $answerUserSession): self
    {
        if (!$this->answerUserSessions->contains($answerUserSession)) {
            $this->answerUserSessions[] = $answerUserSession;
            $answerUserSession->setUser($this);
        }

        return $this;
    }

    public function removeAnswerUserSession(AnswerUserSession $answerUserSession): self
    {
        if ($this->answerUserSessions->removeElement($answerUserSession)) {
            // set the owning side to null (unless already changed)
            if ($answerUserSession->getUser() === $this) {
                $answerUserSession->setUser(null);
            }
        }

        return $this;
    }

    public function getActivationId(): ?string
    {
        return $this->activation_id;
    }

    public function setActivationId(string $activation_id): self
    {
        $this->activation_id = $activation_id;

        return $this;
    }

    public function getActivated(): ?bool
    {
        return $this->activated;
    }

    public function setActivated(bool $activated): self
    {
        $this->activated = $activated;

        return $this;
    }

}
