<?php

namespace App\Repository;

use App\Entity\UserApi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserApi|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserApi|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserApi[]    findAll()
 * @method UserApi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserApiRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserApi::class);
    }

    /**
     * Is the api key already exists?
     * @param $apiKey
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function isApiKeyExists($apiKey) {
        $result = $this->createQueryBuilder('u')
            ->select('COUNT(u)')
            ->andWhere('u.apiKey = :val')
            ->setParameter('val', $apiKey)
            ->getQuery()
            ->getSingleScalarResult();
        return $result > 0;
    }

//    /**
//     * @return UserApi[] Returns an array of UserApi objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserApi
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
