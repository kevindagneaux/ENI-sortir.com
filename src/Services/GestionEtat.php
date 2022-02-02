<?php

namespace App\Services;

use App\Entity\Etat;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class GestionEtat
{

    private SortieRepository $sortieRepository;
    private EntityManagerInterface $entityManager;
    private EtatRepository $etatRepository;

    public function __construct(SortieRepository $sortieRepository, EntityManagerInterface $entityManager, EtatRepository $etatRepository)
    {

        $this->sortieRepository = $sortieRepository;
        $this->entityManager = $entityManager;
        $this->etatRepository = $etatRepository;
    }

    public function updateEtat(){

        $etatOuvert = $this->etatRepository->findOneBy(['libelle' => Etat::OUVERT]);
        $etatCloturer = $this->etatRepository->findOneBy(['libelle' => Etat::CLOTURER]);
        $etatPasser = $this->etatRepository->findOneBy(['libelle' => Etat::PASSER]);
        $etatEnCours = $this->etatRepository->findOneBy(['libelle' => Etat::EN_COURS]);
        $etatArchiver = $this->etatRepository->findOneBy(['libelle' => Etat::ARCHIVER]);

        $sorties = $this->sortieRepository->findSortieOuverte($etatOuvert);
        $this->forEtat($sorties, $etatCloturer);

        $sorties = $this->sortieRepository->findSortieCloturer($etatCloturer);
        $this->forEtat($sorties, $etatEnCours);

        $sorties = $this->sortieRepository->findSortieEnCours($etatEnCours);
        $this->forEtat($sorties, $etatPasser);

        $sorties = $this->sortieRepository->findSortiePasser($etatPasser);
        $this->forEtat($sorties, $etatArchiver);

        $this->entityManager->flush();
    }

    private function forEtat($sorties, $etat){
        foreach ($sorties as $sortie){
            $sortie->setEtat($etat);
            $this->entityManager->persist($sortie);
        }
    }
}