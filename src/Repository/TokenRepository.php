<?php

namespace App\Repository;

use App\Entity\Token;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * @method Token|null find($id, $lockMode = null, $lockVersion = null)
 * @method Token|null findOneBy(array $criteria, array $orderBy = null)
 * @method Token[]    findAll()
 * @method Token[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Token::class);
    }

    /**
     * Find a token by its key
     * @param string $key
     * @return Token Returns a Token object
     * @throws NonUniqueResultException|NoResultException
     */
    public function findOneByKey(string $key): Token {
        return $this->createQueryBuilder('t')
            ->andWhere('t.token_key = :val')
            ->setParameter('val', $key)
            ->getQuery()
            ->getSingleResult()
        ;
    }
}
