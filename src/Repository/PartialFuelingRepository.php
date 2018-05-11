<?php

namespace App\Repository;

use App\Entity\PartialFueling;
use App\Entity\Vehicle;
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

    /**
     * Find all partial fuelings for a specific vehicle
     * @param Vehicle $vehicle
     * @return PartialFueling[] Returns an array of PartialFueling objects
     */
    public function findByVehicle(Vehicle $vehicle)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.vehicle = :val')
            ->setParameter('val', $vehicle)
            ->getQuery()
            ->getResult()
        ;
    }
}
