<?php

namespace App\modele;

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
     * @ORM\Column(type="integer")
     */
    private $nbInscriptionMax;

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
}