<?php

namespace App\Entity;

use App\Repository\ArtykulRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArtykulRepository::class)]
class Artykul
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nazwa = null;

    #[ORM\Column(length: 255)]
    private ?string $JednostkaMiary = null;

    #[ORM\ManyToOne(inversedBy: 'Artykuly')]
    private ?Magazyny $magazyny = null;

    #[ORM\Column(nullable: true)]
    private ?int $IloscPrzyjeta = null;

    #[ORM\Column(nullable: true)]
    private ?int $VAT = null;

    #[ORM\Column(nullable: true)]
    private ?float $Cena = null;

    #[ORM\Column(nullable: true)]
    private ?int $IloscWydana = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNazwa(): ?string
    {
        return $this->Nazwa;
    }

    public function setNazwa(string $Nazwa): self
    {
        $this->Nazwa = $Nazwa;

        return $this;
    }

    public function getJednostkaMiary(): ?string
    {
        return $this->JednostkaMiary;
    }

    public function setJednostkaMiary(string $JednostkaMiary): self
    {
        $this->JednostkaMiary = $JednostkaMiary;

        return $this;
    }

    public function getMagazyny(): ?Magazyny
    {
        return $this->magazyny;
    }

    public function setMagazyny(?Magazyny $magazyny): self
    {
        $this->magazyny = $magazyny;

        return $this;
    }

    public function getIloscPrzyjeta(): ?int
    {
        return $this->IloscPrzyjeta;
    }

    public function setIloscPrzyjeta(?int $IloscPrzyjeta): self
    {
        $this->IloscPrzyjeta = $IloscPrzyjeta;

        return $this;
    }

    public function getVAT(): ?int
    {
        return $this->VAT;
    }

    public function setVAT(?int $VAT): self
    {
        $this->VAT = $VAT;

        return $this;
    }

    public function getCena(): ?float
    {
        return $this->Cena;
    }

    public function setCena(?float $Cena): self
    {
        $this->Cena = $Cena;

        return $this;
    }

    public function getIloscWydana(): ?int
    {
        return $this->IloscWydana;
    }

    public function setIloscWydana(?int $IloscWydana): self
    {
        $this->IloscWydana = $IloscWydana;

        return $this;
    }
}
