<?php

namespace App\Repository;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Modele\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use function Symfony\Component\String\s;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    private Security $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Sortie::class);
        $this->security = $security;
    }

    public function searchSortie(Search $search){

        // Version QueryBuilder
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->andWhere('s.siteOrganisateur = :campus')->setParameter('campus', $search->getCampus());
        // todo: ajouter le paramètres de condition pour les sorties vieille de plus d'un mois.
        // $queryBuilder->andWhere('s.dateHeureDebut > datenow'  );
        if ($search->getDateDebut()){
            $queryBuilder->andWhere('s.dateHeureDebut >  :datedebut')->setParameter('datedebut', $search->getDateDebut());
        }
        if ($search->getDateFin()){
            $queryBuilder->andWhere('s.dateHeureDebut <  :datefin')->setParameter('datefin', $search->getDateFin());
        }
        if ($search->getOrganisateur()){
            $queryBuilder->andWhere('s.organisateur = :organisateur ')->setParameter('organisateur', $this->security->getUser());
        }
        if ($search->getInscrit()){
            $queryBuilder->andWhere(':user member of s.users')->setParameter('user', $this->security->getUser());
            $queryBuilder->andWhere('s.organisateur != :organisateur ')->setParameter('organisateur', $this->security->getUser());
        }
        if ($search->getNonInscrit()){
            $queryBuilder->andWhere(':user2 not member of s.users')->setParameter('user2', $this->security->getUser());
        }
        if ($search->getFini()){
            $queryBuilder->andWhere('s.dateHeureDebut <= :fini')->setParameter('fini', date('Y-m-d H:i:s') );
        }
        if ($search->getSearch()){
            $queryBuilder->andWhere('s.nom like :search')->setParameter('search', '%'.$search->getSearch().'%');
        }
        $query = $queryBuilder->getQuery();

        // Ligne utilisé dans les deux versions.
        $query->setMaxResults(20);
        $result = $query->getResult();

        return $result;

    }

    // Méthode pour retrouvé toute les sorties ouverte dont la date est inférieur ou égal à aujourd'hui
    public function findSortieOuverte(?Etat $etat)
    {
        $qb = $this -> createQueryBuilder('s')
            ->andWhere('s.etat = :etat' )->setParameter('etat', $etat)
            ->andWhere('s.dateLimiteInscription <= :debut')->setParameter('debut', new \DateTime());

        return $qb->getQuery()->getResult();
    }

    // Méthode pour retrouvé toute les sorties clorutée dont la date est inférieur ou égal à aujourd'hui
    public function findSortieCloturer(?Etat $etat)
    {
        $qb = $this -> createQueryBuilder('s')
            ->andWhere('s.etat = :etat' )->setParameter('etat', $etat)
            ->andWhere('s.dateHeureDebut <= :debut')->setParameter('debut', new \DateTime());

        return $qb->getQuery()->getResult();
    }

    // Méthode pour retrouvé toute les sorties en cours dont la date + durée est inférieur ou égal à aujourd'hui
    public function findSortieEnCours(?Etat $etat)
    {
        $qb = $this -> createQueryBuilder('s')
            ->andWhere('s.etat = :etat' )->setParameter('etat', $etat)
            // todo: gérer la fonction par rapport a la durée d'une sortie
            ->andWhere('s.dateHeureDebut <= :debut')->setParameter('debut', DATE_ADD(new \DateTime(),  date_interval_create_from_date_string("1 days")));

        return $qb->getQuery()->getResult();
    }

    // Méthode pour retrouvé toute les sorties passée dont la date est inférieur ou égal à aujourd'hui - 1 mois
    public function findSortiePasser(?Etat $etat)
    {
        $qb = $this -> createQueryBuilder('s')
            ->andWhere('s.etat = :etat' )->setParameter('etat', $etat)
            ->andWhere('s.dateHeureDebut <= :debut')->setParameter('debut', DATE_ADD(new \DateTime(),  date_interval_create_from_date_string("-30 days")));

        return $qb->getQuery()->getResult();
    }

}
