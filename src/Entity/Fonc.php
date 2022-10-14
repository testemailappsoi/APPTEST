<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FoncRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: FoncRepository::class)]
#[ORM\Index(columns: ['nom_fonc','autre'], flags: ['fulltext'] )]
class Fonc
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $NomFonc;

    #[ORM\Column(type: 'string', length: 255)]
    private $Autre;

    #[ORM\OneToMany(mappedBy: 'Fonc', targetEntity: Question::class)]
    private $questions;

    

    #[ORM\ManyToOne(targetEntity: Rout::class, inversedBy: 'foncs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank()]
    private $rout;

    

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->NomFonc; 
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFonc(): ?string
    {
        return $this->NomFonc;
    }

    public function setNomFonc(string $NomFonc): self
    {
        $this->NomFonc = $NomFonc;

        return $this;
    }

    public function getAutre(): ?string
    {
        return $this->Autre;
    }

    public function setAutre(string $Autre): self
    {
        $this->Autre = $Autre;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setFonc($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getFonc() === $this) {
                $question->setFonc(null);
            }
        }

        return $this;
    }


    public function getRout(): ?Rout
    {
        return $this->rout;
    }

    public function setRout(?Rout $rout): self
    {
        $this->rout = $rout;

        return $this;
    }

    
}
