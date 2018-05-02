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
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TokenBO
{

    /**
     * The entity manager
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * DAO Token
     * @var TokenRepository
     */
    private $repository;

    /**
     * TokenBO constructor.
     * @param ObjectManager $entityManager
     * @param TokenRepository $repository
     */
    public function __construct(ObjectManager $entityManager, TokenRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    /**
     * Is the token still valid ?
     * @param Token $token
     * @return bool
     * @throws \Exception|TokenException
     */
    public function isValid(Token $token) {
        if ($token->isValidated()) {
            throw new TokenException("Token already validated!");
        }
        if ($token->getTimeLimit() === -1) {
            return true;
        }
        $cDate = clone $token->getCreatedAt();
        $cDate->add(new \DateInterval('PT' . $token->getTimeLimit() . 'S'));
        $now = new \DateTime();
        return $cDate > $now;
    }

}