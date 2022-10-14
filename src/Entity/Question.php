<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[Vich\Uploadable]
#[ORM\Index(columns: ['question', 'faq', 'solution'], flags: ['fulltext'])]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 855)]
    private $Question;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime')]
    private $DateQuest;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private $User;

    #[ORM\ManyToOne(targetEntity: Fonc::class, inversedBy: 'questions')]
    private $Fonc;

    #[Vich\UploadableField(mapping: 'question_image', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[Vich\UploadableField(mapping: 'Reponse_image', fileNameProperty: 'imageR')]
    private ?File $imageRep = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private $imageR;

    #[ORM\Column(type: 'string', nullable: true)]
    private $imageName;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $Reponse;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'R')]
    #[ORM\JoinColumn(nullable: true)]
    private $Responsable;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'update')]
    private $updateAt;

    #[ORM\Column(type: 'string', length: 355, nullable: true)]
    private $FAQ;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $Finished = false;

    #[ORM\Column(type: 'string', length: 355, nullable: true)]
    private $Solution;

    #[ORM\Column(type: 'boolean')]
    private $isRead = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->Question;
    }

    public function setQuestion(string $Question): self
    {
        $this->Question = $Question;

        return $this;
    }

    public function getDateQuest(): ?\DateTimeInterface
    {
        return $this->DateQuest;
    }



    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getFonc(): ?Fonc
    {
        return $this->Fonc;
    }

    public function setFonc(?Fonc $Fonc): self
    {
        $this->Fonc = $Fonc;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) {
            $this->updateAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setDateQuest(\DateTimeInterface $DateQuest): self
    {
        $this->DateQuest = $DateQuest;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->Reponse;
    }

    public function setReponse(string $Reponse): self
    {
        $this->Reponse = $Reponse;

        return $this;
    }

    public function getResponsable(): ?User
    {
        return $this->Responsable;
    }

    public function setResponsable(?User $Responsable): self
    {
        $this->Responsable = $Responsable;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt()
    {
        $this->updateAt = new \DateTimeImmutable();

        return $this;
    }

    public function __toString()
    {
        return $this->Question;
        return $this->User;
        return $this->Fonc;
        return $this->imageName;
    }

    public function __construct()
    {
        $this->updateAt = new \DateTimeImmutable();
    }

    public function getFAQ(): ?string
    {
        return $this->FAQ;
    }

    public function setFAQ(?string $FAQ): self
    {
        $this->FAQ = $FAQ;

        return $this;
    }

    public function isFinished(): ?bool
    {
        return $this->Finished;
    }

    public function setFinished(?bool $Finished): self
    {
        $this->Finished = $Finished;

        return $this;
    }

    public function getSolution(): ?string
    {
        return $this->Solution;
    }

    public function setSolution(?string $Solution): self
    {
        $this->Solution = $Solution;

        return $this;
    }

    public function isIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function setImageRep(?File $imageRep = null): void
    {
        $this->imageRep = $imageRep;
        if (null !== $imageRep) {
            $this->updateAt = new \DateTimeImmutable();
        }
    }

    public function getImageRep(): ?File
    {
        return $this->imageRep;
    }

    public function setImageR(?string $imageR): void
    {
        $this->imageR = $imageR;
    }

    public function getImageR(): ?string
    {
        return $this->imageR;
    }
}
