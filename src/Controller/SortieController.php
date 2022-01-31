<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\SortieAnnulerType;
use App\Form\SortieType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $sortieForm->remove('supprimer');

        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            // Si le bouton enregitrer est cliqué on passe l'état créée, si c'est le bouton publié on pase l'état publié
            $etatTemp = $sortieForm->get('etat')->isClicked() ? 'Créée' : 'Ouverte';
            $etat = $etatRepository->findOneBy(['libelle' => $etatTemp]);


            $campus = $campusRepository->findOneBy(['id' => $this->getUser()->getParticipantCampus()]);

            $sortie->setEtat($etat);
            $sortie->setSiteOrganisateur($campus);
            $sortie->setOrganisateur($this->getUser());

            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->render("sortie/ajouter.html.twig", [
            "sortieForm" => $sortieForm->createView(),

        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/sortie/villeLieus",name="sortie_listLieusEnFonctionDesVilles")
     */

    public function listLieusEnFonctionDesVilles(Request $request, EntityManagerInterface $em)
    {
        $lieuRepository = $em->getRepository("App:Lieu");

        $lieus = $lieuRepository->createQueryBuilder("q")
            ->where("q.lieuVille =:id")
            ->setParameter("id", $request->query->get("id"))
            ->getQuery()
            ->getResult();

        $response = array();
        foreach ($lieus as $lieus) {
            $response[] = array(
                "id" => $lieus->getId(),
                "nom" => $lieus->getNom(),
            );
        }
        return new JsonResponse($response);
    }

    /**
     * @Route("/sortie/annuler/{id}",name="sortie_annuler")
     */

    public function annulerSortie(Request $request, int $id, EntityManagerInterface $em)
    {

        $repositorySortie = $em->getRepository(Sortie::class);
        $sortie = $repositorySortie->find($id);

        $repositoryCampus = $em->getRepository(Campus::class);
        $campus = $repositoryCampus->find($sortie->getSiteOrganisateur());

        $repositoryLieu = $em->getRepository(Lieu::class);
        $lieu = $repositoryLieu->find($sortie->getLieuSortie());

        $repositoryEtat = $em->getRepository(Etat::class);

        $date = date_format($sortie->getDateHeureDebut(), 'Y-m-d');

        $form = $this->createForm(SortieAnnulerType::class, $sortie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $etat = $repositoryEtat->findOneBy(['libelle' => 'Annulé']);
            $sortie->setEtat($etat);
            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute('accueil');

        }
        return $this->render("sortie/annuler.html.twig", [
            "sortieForm" => $form->createView(),
            "sortie" => $sortie,
            "campus" => $campus,
            "lieu" => $lieu,
            "date" => $date,
        ]);

    }

    /**
     * @Route("/sortie/afficher/{id}",name="sortie_afficher")
     */
    public function afficherSortie(Request $request, int $id, EntityManagerInterface $em)
    {

        $repositorySortie = $em->getRepository(Sortie::class);
        $sortie = $repositorySortie->find($id);

        $repositoryCampus = $em->getRepository(Campus::class);
        $campus = $repositoryCampus->find($sortie->getSiteOrganisateur());

        $repositoryLieu = $em->getRepository(Lieu::class);
        $lieu = $repositoryLieu->find($sortie->getLieuSortie());

        $repossitoryVille = $em->getRepository(Ville::class);
        $ville = $repossitoryVille->find($lieu->getLieuVille());

        $dateDebut = date_format($sortie->getDateHeureDebut(), 'Y-m-d');
        $dateFin = date_format($sortie->getDateLimiteInscription(), 'Y-m-d');

        return $this->render("sortie/afficher.html.twig", [

            "sortie" => $sortie,
            "campus" => $campus,
            "lieu" => $lieu,
            "ville" => $ville,
            'dateDebut' => $dateDebut,
            "dateFin" => $dateFin,

        ]);

    }

    /**
     * @Route("/sortie/modifier/{id}",name="sortie_modifier")
     */
    public function modifierSortie(Request $request, int $id, EntityManagerInterface $em)
    {

        $repositorySortie = $em->getRepository(Sortie::class);
        $etatRepository = $em->getRepository(Etat::class);
        $campusRepository = $em->getRepository(Campus::class);

        $sortie = $repositorySortie->find($id);

        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            if ($sortieForm->get('supprimer')->isClicked()) {

                $em->remove($sortie);
                $em->flush();
                return $this->redirectToRoute('accueil');
            }

            $etatTemp = $sortieForm->get('etat')->isClicked() ? 'Créée' : 'Ouverte';
            $etat = $etatRepository->findOneBy(['libelle' => $etatTemp]);

            $campus = $campusRepository->findOneBy(['id' => $this->getUser()->getParticipantCampus()]);

            $sortie->setEtat($etat);
            $sortie->setSiteOrganisateur($campus);
            $sortie->setOrganisateur($this->getUser());

            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->render("sortie/modifier.html.twig", [
            "sortieForm" => $sortieForm->createView(),

        ]);


    }


}

