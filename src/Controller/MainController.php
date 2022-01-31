<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\User;
use App\Form\SearchFormType;
use App\Modele\Search;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     * */
    public function accueil(Request $request, SortieRepository $sortieRepository):Response{

        $search = new Search();
        $search->setCampus($this->getUser()->getParticipantCampus());
        $searchSortie = $this->createForm(SearchFormType::class,  $search );
        $searchSortie->handleRequest($request);

        $sorties = $sortieRepository->searchSortie($search);
        // var_dump($sorties);

        return $this-> render('main/home.html.twig', [
            'sorties' => $sorties,
            'searchSortie' => $searchSortie -> createView(),

        ]);

    }

    /**
     * @Route("/accueil/{id}", name="inscription")
     * */
    public function inscription(int $id, EntityManagerInterface $entityManager, SortieRepository $sortieRepository){

        $user = $this->getUser();
        $sortie = $sortieRepository->find($id);

        if (!$sortie){ throw $this->createNotFoundException('pas connu'); }

        $sortie->addUser($user);

        $entityManager->persist($sortie);
        $entityManager->flush();

        $this->addFlash('success', 'inscription avec succès!');
        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/accueil/desistement/{id}", name="desistement")
     * */
    public function desistement(int $id, EntityManagerInterface $entityManager, SortieRepository $sortieRepository){

        $user = $this->getUser();
        $sortie = $sortieRepository->find($id);

        if (!$sortie){ throw $this->createNotFoundException('pas connu');}

        $sortie->removeUser($user);

        $entityManager->persist($sortie);
        $entityManager->flush();

        $this->addFlash('success', 'Vous avez été desinscrit avec succès!');
        return $this->redirectToRoute('accueil');
    }

}