<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Modele\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

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
            $queryBuilder->andWhere('s.dateHeureDebut > ', $search->getDateDebut());
        }
        if ($search->getDateFin()){
            $queryBuilder->andWhere('s.dateHeureDebut < ', $search->getDateFin());
        }
        if ($search->getOrganisateur()){
            $queryBuilder->andWhere('s.organisateur = :organisateur ')->setParameter('organisateur', $this->security->getUser());
        }
        if ($search->getInscrit()){
            $queryBuilder->andWhere(':user member of s.users')->setParameter('user', $this->security->getUser());
        }
        if ($search->getNonInscrit()){
            $queryBuilder->andWhere(':user2 not member of s.users')->setParameter('user2', $this->security->getUser());
        }
        if ($search->getFini()){
            //todo faire le where sur la date du jour ou en utilisant les données dans la table
            $queryBuilder->andWhere('s.dateHeureDebut < current_date()');
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

}
