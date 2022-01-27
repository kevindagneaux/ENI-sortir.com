<?php

namespace App\Controller;

use App\Form\SearchFormType;
use App\Modele\Search;
use App\Repository\SortieRepository;
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
        $search->setCampus($this->getUser()->getParticipantCampus()); //modifier le get quand il aura été changer dans l'entité
        $searchSortie = $this->createForm(SearchFormType::class,  $search );
        $searchSortie->handleRequest($request);

        $sorties = $sortieRepository->searchSortie($search);
       // var_dump($sorties);

        return $this-> render('main/home.html.twig', [
            'sorties' => $sorties,
            'searchSortie' => $searchSortie -> createView(),

        ]);

    }

}