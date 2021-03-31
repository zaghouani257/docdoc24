<?php

namespace App\Repository;


use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    // /**
    //  * @return Service[] Returns an array of Service objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Service
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    */
    /**
     * @param $value
     * @return Service[]
     */

    public function findallbycategorie($value){
        return  $this->createQueryBuilder('s')
            ->where('s.categorie=:cat')
            ->setParameter('cat',$value)
            ->orderBy('s.libelle')
            ->getQuery()
            ->getResult();
}
    public function serviceforabout(){
        return  $this->createQueryBuilder('s')
            ->orderBy('s.libelle')
            ->getQuery()
            ->setMaxResults(6)
            ->getResult()
            ;
    }
    public function serviceforfooter(){
        return  $this->createQueryBuilder('s')
            ->orderBy('s.libelle')
            ->getQuery()
            ->setMaxResults(6)
            ->getResult()
            ;
    }

}
