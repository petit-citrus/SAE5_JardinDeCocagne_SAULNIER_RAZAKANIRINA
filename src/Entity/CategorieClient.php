<?php

namespace App\Entity;

use App\Repository\CategorieClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieClientRepository::class)]
class CategorieClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'categorieClients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Structure $structure = null;

    /**
     * @var Collection<int, TarifCategorieClient>
     */
    #[ORM\OneToMany(targetEntity: TarifCategorieClient::class, mappedBy: 'categorieClient')]
    private Collection $tarifsCategorieClient;

    public function __construct()
    {
        $this->tarifsCategorieClient = new ArrayCollection();
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

    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    public function setStructure(?Structure $structure): static
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * @return Collection<int, TarifCategorieClient>
     */
    public function getTarifsCategorieClient(): Collection
    {
        return $this->tarifsCategorieClient;
    }

    public function addTarifCategorieClient(TarifCategorieClient $tarif): static
    {
        if (!$this->tarifsCategorieClient->contains($tarif)) {
            $this->tarifsCategorieClient->add($tarif);
            $tarif->setCategorieClient($this);
        }

        return $this;
    }

    public function removeTarifCategorieClient(TarifCategorieClient $tarif): static
    {
        if ($this->tarifsCategorieClient->removeElement($tarif)) {
            if ($tarif->getCategorieClient() === $this) {
                $tarif->setCategorieClient(null);
            }
        }

        return $this;
    }
}
