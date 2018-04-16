<?php

namespace App\Repository;

use App\Entity\Fueling;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Fueling|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fueling|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fueling[]    findAll()
 * @method Fueling[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FuelingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Fueling::class);
    }

//    /**
//     * @return Fueling[] Returns an array of Fueling objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Fueling
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
