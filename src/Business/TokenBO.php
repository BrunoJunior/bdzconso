<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 30/04/18
 * Time: 23:47
 */

namespace App\Business;


use App\Entity\Token;
use App\Exception\TokenException;
use App\Repository\TokenRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class TokenBO
{

    /**
     * The entity manager
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * DAO Token
     * @var TokenRepository
     */
    private TokenRepository $repository;

    /**
     * TokenBO constructor.
     * @param EntityManagerInterface $entityManager
     * @param TokenRepository $repository
     */
    public function __construct(EntityManagerInterface $entityManager, TokenRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    /**
     * Is the token still valid ?
     * @param Token $token
     * @return bool
     * @throws Exception|TokenException
     */
    public function isValid(Token $token): bool {
        if ($token->isValidated()) {
            throw new TokenException("Token already validated!");
        }
        if ($token->getTimeLimit() === -1) {
            return true;
        }
        $cDate = clone $token->getCreatedAt();
        $cDate->add(new DateInterval('PT' . $token->getTimeLimit() . 'S'));
        $now = new DateTime();
        return $cDate > $now;
    }

}
