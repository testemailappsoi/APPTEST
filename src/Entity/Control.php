<?php

namespace App\Entity;

use App\Repository\ControlRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ControlRepository::class)]
class Control
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $NomCont;

    #[ORM\OneToMany(mappedBy: 'control', targetEntity: Rout::class)]
    private $routs;

    #[ORM\ManyToOne(targetEntity: Application::class, inversedBy: 'controls')]
    #[ORM\JoinColumn(nullable: false)]
    private $appli;

    public function __construct()
    {
        $this->routs = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->NomCont;
        return $this->routs; 
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCont(): ?string
    {
        return $this->NomCont;
    }

    public function setNomCont(string $NomCont): self
    {
        $this->NomCont = $NomCont;

        return $this;
    }

    /**
     * @return Collection<int, Rout>
     */
    public function getRouts(): Collection
    {
        return $this->routs;
    }

    public function addRout(Rout $rout): self
    {
        if (!$this->routs->contains($rout)) {
            $this->routs[] = $rout;
            $rout->setControl($this);
        }

        return $this;
    }

    public function removeRout(Rout $rout): self
    {
        if ($this->routs->removeElement($rout)) {
            // set the owning side to null (unless already changed)
            if ($rout->getControl() === $this) {
                $rout->setControl(null);
            }
        }

        return $this;
    }

    public function getAppli(): ?Application
    {
        return $this->appli;
    }

    public function setAppli(?Application $appli): self
    {
        $this->appli = $appli;

        return $this;
    }
}
