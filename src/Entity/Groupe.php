<?php

namespace App\Entity;

use App\Repository\GroupeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[ORM\Entity(repositoryClass: GroupeRepository::class)]
class Groupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide")]

    #[ORM\Column(length: 20)]
    private ?string $nom = null;
    #[Assert\NotBlank(message: "La description ne peut pas être vide")]

    #[ORM\Column(length: 255)]
    private ?string $description = null;
    #[Assert\NotBlank(message: "Il faut choisi un type pour votre groupe")]

    #[ORM\OneToOne(inversedBy: 'groupe', cascade: ['persist', 'remove'])]
    private ?Typegroupe $typegroupe = null;

    #[Assert\NotBlank(message: "La date de creation ne peut pas être vide")]

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datecreation = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;



   
   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTypegroupe(): ?Typegroupe
    {
        return $this->typegroupe;
    }
    

    public function setTypegroupe(?Typegroupe $typegroupe): static
    {
        $this->typegroupe = $typegroupe;

        return $this;
    }

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(\DateTimeInterface $datecreation): static
    {
        $this->datecreation = $datecreation;

        return $this;
    }
    public function __toString()
    {
        return(string)$this->getNom();
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }


}