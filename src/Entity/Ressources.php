<?php

namespace App\Entity;

use App\Repository\RessourcesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RessourcesRepository::class)]
class Ressources
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le titre de ressource ne peut pas être vide ')]
    private ?string $TitreR = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le type de ressource ne peut pas être vide ')]
    private ?string $TypeR = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'L url de ressource ne peut pas être vide ')]
    private ?string $url = null;

    #[ORM\OneToMany(targetEntity: Categorie::class, mappedBy: 'ressource',cascade:["all"],orphanRemoval:true)]
    private Collection $categories;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La description ne peut pas être vide ')]
    private ?string $description = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    

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

    public function setUrl(?string $url): self
{
    $this->url = $url;

    return $this;
}
   
    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setRessource($this);
        }

        return $this;
    }

    public function removeCategory(Categorie $category): static
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getRessource() === $this) {
                $category->setRessource(null);
            }
        }

        return $this;
    }
      
    public function __toString()
    {
        return(string)$this->getTitreR();
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

}
