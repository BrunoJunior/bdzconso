<?php

namespace App\Repository;

use App\Entity\FuelType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FuelType|null find($id, $lockMode = null, $lockVersion = null)
 * @method FuelType|null findOneBy(array $criteria, array $orderBy = null)
 * @method FuelType[]    findAll()
 * @method FuelType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FuelTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FuelType::class);
    }

//    /**
//     * @return FuelType[] Returns an array of FuelType objects
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

    /**
     * Find fuel type by its name
     * @param string $name
     * @return FuelType|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByName(string $name): ?FuelType
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.name = :val')
            ->setParameter('val', $name)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
