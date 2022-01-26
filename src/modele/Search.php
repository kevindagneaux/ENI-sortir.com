<?php

namespace App\modele;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Search extends AbstractController
{
    /**
     * @ORM\Column(type="string", length=150)
     */
    private $campus;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $search;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="boolean")
     */
    private $organisateur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $inscrit;

    /**
     * @ORM\Column(type="boolean")
     */
    private $nonInscrit;

    /**
     * @ORM\Column(type="boolean")
     */
    private $fini;

    public function getCampus()
    {
        return $this->campus;
    }

    public function getSearch()
    {
        return $this->search;
    }

    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    public function getDateFin()
    {
        return $this->dateFin;
    }

    public function getOrganisateur()
    {
        return $this->organisateur;
    }

    public function getInscrit()
    {
        return $this->inscrit;
    }

    public function getNonInscrit()
    {
        return $this->nonInscrit;
    }

    public function getFini()
    {
        return $this->fini;
    }


}