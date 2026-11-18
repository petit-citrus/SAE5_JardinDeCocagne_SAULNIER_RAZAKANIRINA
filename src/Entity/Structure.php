<?php

namespace App\Entity;

use App\Repository\StructureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StructureRepository::class)]
class Structure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $siret = null;

    #[ORM\Column(length: 255)]
    private ?string $iban = null;

    #[ORM\Column]
    private ?bool $assujettiTva = null;

    /**
     * @var Collection<int, CategorieClient>
     */
    #[ORM\OneToMany(targetEntity: CategorieClient::class, mappedBy: 'structure')]
    private Collection $categorieClients;

    /**
     * @var Collection<int, Utilisateur>
     */
    #[ORM\OneToMany(targetEntity: Utilisateur::class, mappedBy: 'structure')]
    private Collection $utilisateurs;

    public function __construct()
    {
        $this->categorieClients = new ArrayCollection();
        $this->utilisateurs = new ArrayCollection();
    }

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

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(string $iban): static
    {
        $this->iban = $iban;

        return $this;
    }

    public function isAssujettiTva(): ?bool
    {
        return $this->assujettiTva;
    }

    public function setAssujettiTva(bool $assujettiTva): static
    {
        $this->assujettiTva = $assujettiTva;

        return $this;
    }

    /**
     * @return Collection<int, CategorieClient>
     */
    public function getCategorieClients(): Collection
    {
        return $this->categorieClients;
    }

    public function addCategorieClient(CategorieClient $categorieClient): static
    {
        if (!$this->categorieClients->contains($categorieClient)) {
            $this->categorieClients->add($categorieClient);
            $categorieClient->setStructure($this);
        }

        return $this;
    }

    public function removeCategorieClient(CategorieClient $categorieClient): static
    {
        if ($this->categorieClients->removeElement($categorieClient)) {
            if ($categorieClient->getStructure() === $this) {
                $categorieClient->setStructure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): static
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs->add($utilisateur);
            $utilisateur->setStructure($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): static
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            if ($utilisateur->getStructure() === $this) {
                $utilisateur->setStructure(null);
            }
        }

        return $this;
    }
}
