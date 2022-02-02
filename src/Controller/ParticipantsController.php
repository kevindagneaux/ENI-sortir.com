<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantsController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{

    /**
     * @Route("/participants/afficher/{id}",name="participant_afficher")
     */
    public function afficherParticipant(Request $request, EntityManagerInterface $em,int $id)
    {
        $userRepo = $em->getRepository(User::class);
        $user = $userRepo->find($id);

        return $this->render("/profil/afficherParticipant.html.twig",[
            "user"=>$user,
        ]);

    }
}