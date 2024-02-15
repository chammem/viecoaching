<?php

namespace App\Entity;

use App\Repository\SeanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: SeanceRepository::class)]
class Seance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"Le titre de la séance ne peut pas être vide.")]
    #[Assert\Length(max:50, maxMessage:"Le titre de la séance ne peut pas dépasser {{ Limit }} caractères.")]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotNull(message:"La durée de la séance doit être spécifiée.")]
    private ?\DateTimeInterface $duree = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotNull(message:"Le lien de la séance doit être spécifiée.")]
    #[Assert\Url(message:"Le lien doit être une URL valide.")]
    private ?string $lien = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotNull(message:"Le mot de passe de la séance doit être spécifiée.")]
    #[Assert\Length(max:50, maxMessage:"Le mot de passe ne peut pas dépasser {{ Limit }} caractères.")]
    private ?string $mot_de_passe = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    #[Assert\NotBlank(message:"Le type de séance doit être spécifié.")]
    private ?TypeSeance $typeSeance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDuree(): ?\DateTimeInterface
    {
        return $this->duree;
    }

    public function setDuree(\DateTimeInterface $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): static
    {
        $this->lien = $lien;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->mot_de_passe;
    }

    public function setMotDePasse(string $mot_de_passe): static
    {
        $this->mot_de_passe = $mot_de_passe;

        return $this;
    }

    public function getTypeSeance(): ?TypeSeance
    {
        return $this->typeSeance;
    }

    public function setTypeSeance(?TypeSeance $typeSeance): static
    {
        $this->typeSeance = $typeSeance;

        return $this;
    }
}
