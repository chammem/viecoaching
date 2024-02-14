<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nomCategorie = null;

    #[ORM\ManyToMany(targetEntity: Ressources::class, mappedBy: 'categorie')]
    private Collection $ressources;

    public function __construct()
    {
        $this->ressources = new ArrayCollection();
    }

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategorie(): ?string
    {
        return $this->nomCategorie;
    }

    public function setNomCategorie(string $nomCategorie): static
    {
        $this->nomCategorie = $nomCategorie;

        return $this;
    }

    /** 
     * @return Collection<int, Ressources>
     */
   

   /**
    * @return Collection<int, Ressources>
    */

   /**
    * @return Collection<int, Ressources>
    */
   public function getRessources(): Collection
   {
       return $this->ressources;
   }

   public function addRessource(Ressources $ressource): static
   {
       if (!$this->ressources->contains($ressource)) {
           $this->ressources->add($ressource);
           $ressource->addCategorie($this);
       }

       return $this;
   }

   public function removeRessource(Ressources $ressource): static
   {
       if ($this->ressources->removeElement($ressource)) {
           $ressource->removeCategorie($this);
       }

       return $this;
   }
   public function __toString()
   {
       return(string)$this->getNomCategorie();
   }
  
}
