<?php

namespace App\Controller;

use App\Form\SearchFormType;
use App\modele\Search;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     * */
    public function accueil(Request $request):Response{

       // $search = new Search();
        $searchSortie = $this->createForm(SearchFormType::class /* $search */);

     /*   if ($searchSortie->handleRequest($request)->isSubmitted() && $searchSortie->isValid()){
            $sortie = $sortieRepository->searchSortie($search);
        }*/
        return $this-> render('main/home.html.twig', [
            'searchSortie' => $searchSortie -> createView()
        ]);

    }

}