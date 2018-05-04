<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    /**
     * Count the number of vehicles for a user
     * @param User $user
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countByUser(User $user):int {
        $resultat = $this->createQueryBuilder('v')
            ->select('COUNT(v)')
            ->andWhere('v.user = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getSingleScalarResult();
        return $resultat;
    }

//    /**
//     * @return Vehicle[] Returns an array of Vehicle objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Vehicle
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
