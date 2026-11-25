<?php

namespace App\Entity;

use App\Repository\PointDepotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PointDepotRepository::class)]
class PointDepot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $contactNom = null;

    #[ORM\Column(length: 255)]
    private ?string $contactTel = null;

    #[ORM\Column(type: 'integer')]
    private ?int $capaciteMax = null;

    #[ORM\Column(length: 255)]
    private ?string $categorie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motDePasse = null;

    #[ORM\ManyToOne(inversedBy: 'pointsDepot')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Structure $structure = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?CategorieClient $categorieClient = null;

    /**
     * @var Collection<int, TourneePointDepot>
     */
    #[ORM\OneToMany(targetEntity: TourneePointDepot::class, mappedBy: 'pointDepot')]
    private Collection $tourneePointsDepot;

    public function __construct()
    {
        $this->tourneePointsDepot = new ArrayCollection();
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getContactNom(): ?string
    {
        return $this->contactNom;
    }

    public function setContactNom(string $contactNom): static
    {
        $this->contactNom = $contactNom;

        return $this;
    }

    public function getContactTel(): ?string
    {
        return $this->contactTel;
    }

    public function setContactTel(string $contactTel): static
    {
        $this->contactTel = $contactTel;

        return $this;
    }

    public function getCapaciteMax(): ?int
    {
        return $this->capaciteMax;
    }

    public function setCapaciteMax(int $capaciteMax): static
    {
        $this->capaciteMax = $capaciteMax;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(?string $motDePasse): static
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    public function setStructure(?Structure $structure): static
    {
        $this->structure = $structure;

        return $this;
    }

    public function getCategorieClient(): ?CategorieClient
    {
        return $this->categorieClient;
    }

    public function setCategorieClient(?CategorieClient $categorieClient): static
    {
        $this->categorieClient = $categorieClient;

        return $this;
    }

    /**
     * @return Collection<int, TourneePointDepot>
     */
    public function getTourneePointsDepot(): Collection
    {
        return $this->tourneePointsDepot;
    }

    public function addTourneePointDepot(TourneePointDepot $tourneePointDepot): static
    {
        if (!$this->tourneePointsDepot->contains($tourneePointDepot)) {
            $this->tourneePointsDepot->add($tourneePointDepot);
            $tourneePointDepot->setPointDepot($this);
        }

        return $this;
    }

    public function removeTourneePointDepot(TourneePointDepot $tourneePointDepot): static
    {
        if ($this->tourneePointsDepot->removeElement($tourneePointDepot)) {
            if ($tourneePointDepot->getPointDepot() === $this) {
                $tourneePointDepot->setPointDepot(null);
            }
        }

        return $this;
    }
}
