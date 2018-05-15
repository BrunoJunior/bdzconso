<?php

namespace App\Repository;

use App\Entity\Fueling;
use App\Entity\FuelType;
use App\Entity\User;
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
     * Get Fuelings for a vehicle order by descending dates between $start and $end
     * By default, load the current year fuelings
     * @param Vehicle $vehicle
     * @param \DateTime $start
     * @param \DateTime $end
     * @return Fueling[]
     * @throws \Exception
     */
    public function findByVehicleWithDateLimit(Vehicle $vehicle, \DateTime $start = null, \DateTime $end = null) {
        if ($end === null) {
            $end = new \DateTime();
        }
        if ($start === null) {
            $start = clone $end;
            $start->sub(new \DateInterval("P1Y"));
        }
        return $this->createQueryBuilder('f')
            ->andWhere('f.vehicle = :val')
            ->andWhere('f.date > :start and f.date <= :end')
            ->setParameter('val', $vehicle)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('f.date', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Get Fuelings for a vehicle order by descending dates for the previous year
     * @param Vehicle $vehicle
     * @return Fueling[]
     * @throws \Exception
     */
    public function findPreviousYearByVehicle(Vehicle $vehicle) {
        $oneYearInterval = new \DateInterval("P1Y");
        $end = new \DateTime();
        $end->sub($oneYearInterval);
        $start = clone $end;
        $start->sub($oneYearInterval);
        return $this->findByVehicleWithDateLimit($vehicle, $start, $end);
    }

    /**
     * Get Fuelings for a user order by descending dates during the current year
     * @param Vehicle $vehicle
     * @return Fueling[]
     */
    public function findCurrentYearByUser(User $user) {
        $limitDate = new \DateTime();
        $limitDate->sub(new \DateInterval("P1Y"));
        return $this->createQueryBuilder('f')
            ->innerJoin('f.vehicle', 'v')
            ->andWhere('v.user = :val')
            ->andWhere('f.date > :date')
            ->setParameter('val', $user)
            ->setParameter('date', $limitDate)
            ->orderBy('f.date', 'DESC')
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

    /**
     * Count the number of fuelings for a user
     * @param User $user
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countByUser(User $user):int {
        $resultat = $this->createQueryBuilder('f')
            ->select('COUNT(f)')
            ->innerJoin('f.vehicle', 'v')
            ->andWhere('v.user = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getSingleScalarResult();
        return $resultat;
    }

    /**
     * Get Fuelings for an user order by descending dates
     * @param User $user
     * @param int $pageNumber
     * @param int $maxByPage
     * @return Fueling[]
     */
    public function findByUser(User $user, $pageNumber = 1, $maxByPage = 10) {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.vehicle', 'v')
            ->andWhere('v.user = :val')
            ->setParameter('val', $user)
            ->orderBy('f.date', 'DESC')
            ->setFirstResult(($pageNumber - 1) * $maxByPage)
            ->setMaxResults($maxByPage)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTotalTraveledDistance(User $user = null) {
        $builder = $this->createQueryBuilder('f')
            ->select('SUM(f.traveledDistance)');
        if ($user !== null) {
            $builder->innerJoin('f.vehicle', 'v')
                ->andWhere('v.user = :user')
                ->setParameter('user', $user);
        }
        return $builder->getQuery()->getSingleScalarResult();
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTotalConsumedVolume(User $user = null) {
        $builder = $this->createQueryBuilder('f')
            ->select('SUM(f.volume)');
        if ($user !== null) {
            $builder->innerJoin('f.vehicle', 'v')
                ->andWhere('v.user = :user')
                ->setParameter('user', $user);
        }
        return $builder->getQuery()->getSingleScalarResult();
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTotalAmount(User $user = null) {
        $builder = $this->createQueryBuilder('f')
            ->select('SUM(f.amount)');
        if ($user !== null) {
            $builder->innerJoin('f.vehicle', 'v')
                ->andWhere('v.user = :user')
                ->setParameter('user', $user);
        }
        return $builder->getQuery()->getSingleScalarResult();
    }

    /**
     * Get average volume prices grouped by date
     * @param FuelType $fuelType
     * @return array
     */
    public function getAverageVolumePrices(FuelType $fuelType) {
        return $this->createQueryBuilder('f')
            ->select('AVG(f.volumePrice) AS volumePrice, f.date')
            ->andWhere('f.fuelType = :val')
            ->setParameter('val', $fuelType)
            ->orderBy('f.date', 'DESC')
            ->groupBy('f.date')
            ->getQuery()
            ->getArrayResult();
    }
}
