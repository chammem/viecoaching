<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\EntityListeners(['App\EntityListener\UserListener'])]
#[UniqueEntity(fields: ['email'], message: 'Cette adresse e-mail est déjà utilisée.')]
#[Vich\Uploadable]
class Utilisateur implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    private string $nom ;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: "Le prénom ne peut pas être vide.")]
    private string $prenom ;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'email ne peut pas être vide.")]
    #[Assert\Email(
        message: "L'email '{{ value }}' n'est pas un email valide.",
    )]
    private string $email ;

    #[ORM\Column(type: Types::BIGINT)]
    #[Assert\NotBlank(message: "Le numéro de téléphone ne peut pas être vide.")]
    #[Assert\Length(
        min: 8,
        max: 15,
        minMessage: "Le numéro de téléphone doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le numéro de téléphone ne peut pas dépasser {{ limit }} caractères."
    )]
    private string $tel ;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le mot de passe ne peut pas être vide.")]
    #[Assert\Length(
        min: 8,
        minMessage: "Le mot de passe doit contenir au moins {{ limit }} caractères."
    )]
    private string $mdp ;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: "Le genre ne peut pas être vide.")]
    private string $genre ;

    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'utilisateurs')]
    private ?Role $role = null;
    #[Vich\UploadableField(mapping:'user_images',fileNameProperty:'image')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable : true)]
    private ?string $image = null;
    
    
    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: "La ville ne peut pas être vide.")]
    private string $ville ;

    #[ORM\Column(length: 30)]
    private $active;

    public function __construct()
    {
        $this->active = true; // Initialisation à true par défaut
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTel(): string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getMdp(): string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }
    
    public function setRole(?Role $role): static
    {
        $this->role = $role;
    
        return $this;
    }

    public function getRoles(): array
{
    // Si l'utilisateur a un rôle défini, utilisez-le
    if ($this->role) {
        return [$this->role->getNomRole()];
    }

    // Sinon, retournez un rôle par défaut
    return ['ROLE_USER'];
}
public function getUsername(): string
    {
        return $this->email;
    }
    public function getPassword(): string
    {
        return $this->mdp;
    }

    public function getSalt(): ?string
    {
       
        return null;
    }
    
    public function eraseCredentials(): void
    {
        // Cette méthode est utilisée pour effacer les données sensibles de l'objet utilisateur
        
    }
    public function getUserIdentifier(): string
{
    return $this->email;
}

public function getImageFile(): ?File
{
    return $this->imageFile;
}

public function setImageFile(?File $imageFile=null): self

{
    $this->imageFile = $imageFile;

    return $this;
}

public function getImage(): ?string
{
    return $this->image;
}

public function setImage(?string $image): self
{
    $this->image = $image;

    return $this;
}

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
