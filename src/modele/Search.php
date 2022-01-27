<?php

namespace App\Modele;


use App\Entity\Campus;

class Search
{
    private Campus $campus;

    private ?string $search = null;

    private ?\DateTime $dateDebut = null;

    private ?\DateTime $dateFin = null;

    private ?bool $organisateur = false;

    private ?bool $inscrit = false;

    private ?bool $nonInscrit = false;

    private ?bool $fini = false;


    public function getCampus(): Campus
    {
        return $this->campus;
    }

    public function setCampus(Campus $campus): void
    {
        $this->campus = $campus;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }

    public function getDateDebut(): ?\DateTime
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTime $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    public function getDateFin(): ?\DateTime
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTime $dateFin): void
    {
        $this->dateFin = $dateFin;
    }

    public function getOrganisateur(): ?bool
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?bool $organisateur): void
    {
        $this->organisateur = $organisateur;
    }

    public function getInscrit(): ?bool
    {
        return $this->inscrit;
    }

    public function setInscrit(?bool $inscrit): void
    {
        $this->inscrit = $inscrit;
    }

    public function getNonInscrit(): ?bool
    {
        return $this->nonInscrit;
    }

    public function setNonInscrit(?bool $nonInscrit): void
    {
        $this->nonInscrit = $nonInscrit;
    }

    public function getFini(): ?bool
    {
        return $this->fini;
    }

    public function setFini(?bool $fini): void
    {
        $this->fini = $fini;
    }



}