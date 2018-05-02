<?php

namespace App\Repository;

use App\Entity\Token;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Token|null find($id, $lockMode = null, $lockVersion = null)
 * @method Token|null findOneBy(array $criteria, array $orderBy = null)
 * @method Token[]    findAll()
 * @method Token[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TokenRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Token::class);
    }

    /**
     * Find a token by its key
     * @param string $key
     * @return Token Returns a Token object
     * @throws NonUniqueResultException|NoResultException
     */
    public function findOneByKey(string $key)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.token_key = :val')
            ->setParameter('val', $key)
            ->getQuery()
            ->getSingleResult()
        ;
    }
}