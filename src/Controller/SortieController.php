<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\SortieAnnulerType;
use App\Form\SortieType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;
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

        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            // Si le bouton enregitrer est cliqué on passe l'état créée, si c'est le bouton publié on pase l'état publié
            $etatTemp = $sortieForm->get('etat')->isClicked() ? 'Créée' : 'Ouverte';
            $etat = $etatRepository->findOneBy(['libelle' => $etatTemp]);

            // todo il faudra faire en sorte de recup l'id de l'user
            $campus = $campusRepository->findOneBy(['id' => '1']);

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
    $sortie = new Sortie();
    $campus = new Campus();
    $lieu = new Lieu();

        $RepositorySortie = $em->getRepository(Sortie::class);
        $sortie = $RepositorySortie->find($id);

        $RepositoryCampus = $em->getRepository(Campus::class);
        $campus = $RepositoryCampus->find($sortie->getSiteOrganisateur());

        $RepositoryLieu = $em->getRepository(Lieu::class);
        $lieu = $RepositoryLieu->find($sortie->getLieuSortie());

        $RepositoryEtat = $em->getRepository(Etat::class);

        $date = date_format($sortie->getDateHeureDebut(),'Y-m-d');

        $form = $this->createForm(SortieAnnulerType::class, $sortie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $etat = $RepositoryEtat->findOneBy(['libelle' => 'Annulé']);
            $sortie->setEtat($etat);
            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute('accueil');

        }
        return $this->render("sortie/annuler.html.twig", [
            "sortieForm" => $form->createView(),
            "sortie" => $sortie,
            "campus"=> $campus,
            "lieu"=>$lieu,
            "date"=>$date,
        ]);

    }
}

