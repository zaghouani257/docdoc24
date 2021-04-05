<?php

namespace App\Repository;

use App\Entity\CategorieService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategorieService|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieService|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieService[]    findAll()
 * @method CategorieService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieService::class);
    }

    public function searchCategorie($var)
    {
        return $this->createQueryBuilder('c')
            ->where('c.libelle Like :var')
            ->orWhere('c.description lIKE :var')
            ->setParameter('var', '%'.$var.'%')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return CategorieService[] Returns an array of CategorieService objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategorieService
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
