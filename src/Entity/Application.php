<?php

namespace App\Entity;

use App\Repository\ApplicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
#[ORM\Index(columns: ['nom_app'], flags: ['fulltext'] )]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Version;

    #[ORM\Column(type: 'string', length: 255)]
    private $NomApp;


    #[ORM\OneToMany(mappedBy: 'appli', targetEntity: Control::class)]
    private $controls;

   

   

    public function __construct()
    {
        $this->controls = new ArrayCollection();
        
    }

    public function __toString()
    {
        return $this->NomApp; 
        return $this->controls; 
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVersion(): ?string
    {
        return $this->Version;
    }

    public function setVersion(string $Version): self
    {
        $this->Version = $Version;

        return $this;
    }

    public function getNomApp(): ?string
    {
        return $this->NomApp;
    }

    public function setNomApp(string $NomApp): self
    {
        $this->NomApp = $NomApp;

        return $this;
    }






    /**
     * @return Collection<int, Control>
     */
    public function getControls(): Collection
    {
        return $this->controls;
    }

    public function addControl(Control $control): self
    {
        if (!$this->controls->contains($control)) {
            $this->controls[] = $control;
            $control->setAppli($this);
        }

        return $this;
    }

    public function removeControl(Control $control): self
    {
        if ($this->controls->removeElement($control)) {
            // set the owning side to null (unless already changed)
            if ($control->getAppli() === $this) {
                $control->setAppli(null);
            }
        }

        return $this;
    }

   

   
}
