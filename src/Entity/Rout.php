<?php

namespace App\Entity;

use App\Repository\RoutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoutRepository::class)]
class Rout
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $NomRout;

    #[ORM\ManyToOne(targetEntity: Control::class, inversedBy: 'routs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank() ]
    private $control;

    #[ORM\OneToMany(mappedBy: 'rout', targetEntity: Fonc::class)]
    private $foncs;

    public function __construct()
    {
        $this->foncs = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->NomRout;
        return $this->control; 
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomRout(): ?string
    {
        return $this->NomRout;
    }

    public function setNomRout(string $NomRout): self
    {
        $this->NomRout = $NomRout;

        return $this;
    }

    public function getControl(): ?Control
    {
        return $this->control;
    }

    public function setControl(?Control $control): self
    {
        $this->control = $control;

        return $this;
    }

    /**
     * @return Collection<int, Fonc>
     */
    public function getFoncs(): Collection
    {
        return $this->foncs;
    }

    public function addFonc(Fonc $fonc): self
    {
        if (!$this->foncs->contains($fonc)) {
            $this->foncs[] = $fonc;
            $fonc->setRout($this);
        }

        return $this;
    }

    public function removeFonc(Fonc $fonc): self
    {
        if ($this->foncs->removeElement($fonc)) {
            // set the owning side to null (unless already changed)
            if ($fonc->getRout() === $this) {
                $fonc->setRout(null);
            }
        }

        return $this;
    }
}
