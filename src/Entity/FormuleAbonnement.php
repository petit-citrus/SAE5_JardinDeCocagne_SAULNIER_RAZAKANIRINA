<?php

namespace App\Entity;

use App\Repository\FormuleAbonnementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormuleAbonnementRepository::class)]
class FormuleAbonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'formulesAbonnement')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Structure $structure = null;

    #[ORM\ManyToOne(inversedBy: 'formulesAbonnement')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(inversedBy: 'formulesAbonnement')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Frequence $frequence = null;

    /**
     * @var Collection<int, AbonnementClient>
     */
    #[ORM\OneToMany(targetEntity: AbonnementClient::class, mappedBy: 'formuleAbonnement')]
    private Collection $abonnementsClients;

    public function __construct()
    {
        $this->abonnementsClients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getFrequence(): ?Frequence
    {
        return $this->frequence;
    }

    public function setFrequence(?Frequence $frequence): static
    {
        $this->frequence = $frequence;

        return $this;
    }

    /**
     * @return Collection<int, AbonnementClient>
     */
    public function getAbonnementsClients(): Collection
    {
        return $this->abonnementsClients;
    }

    public function addAbonnementClient(AbonnementClient $abonnement): static
    {
        if (!$this->abonnementsClients->contains($abonnement)) {
            $this->abonnementsClients->add($abonnement);
            $abonnement->setFormuleAbonnement($this);
        }

        return $this;
    }

    public function removeAbonnementClient(AbonnementClient $abonnement): static
    {
        if ($this->abonnementsClients->removeElement($abonnement)) {
            if ($abonnement->getFormuleAbonnement() === $this) {
                $abonnement->setFormuleAbonnement(null);
            }
        }

        return $this;
    }
}
