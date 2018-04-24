<?php

namespace App\Repository;

use App\Entity\Fueling;
use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Psr\Log\LoggerInterface;
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

    /**
     * Get Fuelings for a vehicle order by descending dates
     * @param Vehicle $vehicle
     * @param int $pageNumber
     * @param int $maxByPage
     * @return Fueling[]
     */
    public function findByVehicle(Vehicle $vehicle, $pageNumber = 1, $maxByPage = 10) {
        return $this->createQueryBuilder('f')
            ->andWhere('f.vehicle = :val')
            ->setParameter('val', $vehicle)
            ->orderBy('f.date', 'DESC')
            ->setFirstResult(($pageNumber - 1) * $maxByPage)
            ->setMaxResults($maxByPage)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Count the number of fuelings for a vehicle
     * @param Vehicle $vehicle
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countByVehicle(Vehicle $vehicle):int {
        $resultat = $this->createQueryBuilder('f')
            ->select('COUNT(f)')
            ->andWhere('f.vehicle = :val')
            ->setParameter('val', $vehicle)
            ->getQuery()
            ->getSingleScalarResult();
        return $resultat;
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
