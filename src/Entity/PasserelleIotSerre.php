<?php

namespace App\Entity;

use App\Repository\PasserelleIotSerreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PasserelleIotSerreRepository::class)]
class PasserelleIotSerre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomSerre = null;

    #[ORM\Column(length: 255)]
    private ?string $urlApi = null;

    #[ORM\Column(length: 255)]
    private ?string $apiKey = null;

    #[ORM\ManyToOne(inversedBy: 'passerellesIotSerre')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Structure $structure = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSerre(): ?string
    {
        return $this->nomSerre;
    }

    public function setNomSerre(string $nomSerre): static
    {
        $this->nomSerre = $nomSerre;

        return $this;
    }

    public function getUrlApi(): ?string
    {
        return $this->urlApi;
    }

    public function setUrlApi(string $urlApi): static
    {
        $this->urlApi = $urlApi;

        return $this;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): static
    {
        $this->apiKey = $apiKey;

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
}
