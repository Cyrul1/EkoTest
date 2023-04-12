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
}
