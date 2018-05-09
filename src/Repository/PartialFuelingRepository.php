<?php

namespace App\Repository;

use App\Entity\PartialFueling;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PartialFueling|null find($id, $lockMode = null, $lockVersion = null)
 * @method PartialFueling|null findOneBy(array $criteria, array $orderBy = null)
 * @method PartialFueling[]    findAll()
 * @method PartialFueling[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartialFuelingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PartialFueling::class);
    }

//    /**
//     * @return PartialFueling[] Returns an array of PartialFueling objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PartialFueling
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
