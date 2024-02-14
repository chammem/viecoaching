<?php

namespace App\Entity;

use App\Repository\TypegroupeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Asser;


#[ORM\Entity(repositoryClass: TypegroupeRepository::class)]
class Typegroupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomtype = null;

    #[ORM\OneToOne(mappedBy: 'typegroupe', cascade: ['persist', 'remove'])]
    private ?Groupe $groupe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomtype(): ?string
    {
        return $this->nomtype;
    }

    public function setNomtype(string $nomtype): static
    {
        $this->nomtype = $nomtype;

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): static
    {
        // unset the owning side of the relation if necessary
        if ($groupe === null && $this->groupe !== null) {
            $this->groupe->setTypegroupe(null);
        }

        // set the owning side of the relation if necessary
        if ($groupe !== null && $groupe->getTypegroupe() !== $this) {
            $groupe->setTypegroupe($this);
        }

        $this->groupe = $groupe;

        return $this;
    }
    public function __toString()
    {
        return(string)$this->getNomtype();
    }

}
