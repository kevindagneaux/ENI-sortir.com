<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie/ajouter",name="sortie_ajouter")
     */
    public function ajouter(Request $request, EntityManagerInterface $entityManager, EtatRepository $etatRepository, CampusRepository $campusRepository)
    {
        $sortie = new Sortie();

        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        // Si le bouton annuler cliqué on redirige sur la page d'accueil
        if ($sortieForm->get('annuler')->isClicked()) {
            return $this->render("main/home.html.twig");
        }

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            // Si le bouton enregitrer est cliqué on passe l'état créée, si c'est le bouton publié on pase l'état publié
            $etatTemp = $sortieForm->get('etat')->isClicked() ? 'Créée' : 'Ouverte';
            $etat = $etatRepository->findOneBy(['libelle' => $etatTemp]);

            // todo il faudra faire en sorte de recup l'id de l'user
            $campus = $campusRepository->findOneBy(['id' => '1']);

            $sortie->setEtat($etat);
            $sortie->setSiteOrganisateur($campus);

            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->render("main/home.html.twig");
        }

        return $this->render("sortie/ajouter.html.twig", [
            "sortieForm" => $sortieForm->createView(),

        ]);
    }

}
