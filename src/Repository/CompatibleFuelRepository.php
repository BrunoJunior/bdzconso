<?php

namespace App\Repository;

use App\Entity\CompatibleFuel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CompatibleFuel|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompatibleFuel|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompatibleFuel[]    findAll()
 * @method CompatibleFuel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompatibleFuelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CompatibleFuel::class);
    }

//    /**
//     * @return CompatibleFuel[] Returns an array of CompatibleFuel objects
//     */
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
    public function findOneBySomeField($value): ?CompatibleFuel
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
