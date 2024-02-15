<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\EntityListeners(['App\EntityListener\UserListener'])]
class Utilisateur implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $nom = null;

    #[ORM\Column(length: 30)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $tel = null;

    #[ORM\Column(length: 255)]
    private ?string $mdp = null;

    #[ORM\Column(length: 30)]
    private ?string $genre = null;

    #[ORM\ManyToOne(inversedBy: 'utilisateurs')]
    private ?Role $role = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 30)]
    private ?string $ville = null;


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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): static
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
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
}
