<?php

namespace App\Entity;

use App\Repository\RessourcesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\Cast\String_;

#[ORM\Entity(repositoryClass: RessourcesRepository::class)]
class Ressources
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $TitreR = null;

    #[ORM\Column(length: 255)]
    private ?string $TypeR = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'ressources')]
    private Collection $categorie;

    public function __construct()
    {
        $this->categorie = new ArrayCollection();
    }

    /*#[ORM\ManyToOne(inversedBy: 'ressources')]
    private ?Categorie $categorie = null;*/

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreR(): ?string
    {
        return $this->TitreR;
    }

    public function setTitreR(string $TitreR): static
    {
        $this->TitreR = $TitreR;

        return $this;
    }

    public function getTypeR(): ?string
    {
        return $this->TypeR;
    }

    public function setTypeR(string $TypeR): static
    {
        $this->TypeR = $TypeR;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

   /* public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }*/

   /**
    * @return Collection<int, Categorie>
    */
   public function getCategorie(): Collection
   {
       return $this->categorie;
   }

   public function addCategorie(Categorie $categorie): static
   {
       if (!$this->categorie->contains($categorie)) {
           $this->categorie->add($categorie);
       }

       return $this;
   }

   public function removeCategorie(Categorie $categorie): static
   {
       $this->categorie->removeElement($categorie);

       return $this;
   }
   public function __toString()
   {
    return(string)$this->getTitreR();
   }
    
}
