<?php

namespace App\Entity;

use App\Business\FuelingBO;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FuelingRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Fueling extends SuperFueling
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Created by saved partial fueling
     * @ORM\Column(type="boolean")
     * @var bool
     */
    protected $fromPartial = false;


    public function getId()
    {
        return $this->id;
    }

    /**
     * Calculed consumption
     * @return float
     */
    public function getRealConsumption(): ?float
    {
        if (!$this->traveledDistance) {
            return -1.0;
        }
        // (l * 1000) ml / (100km / 1000) hm
        // l/100km
        return $this->volume / $this->traveledDistance;
    }

    /**
     * @return bool
     */
    public function isFromPartial(): bool
    {
        return $this->fromPartial;
    }

    /**
     * @param bool $fromPartial
     * @return Fueling
     */
    public function setFromPartial(bool $fromPartial): Fueling
    {
        $this->fromPartial = $fromPartial;
        return $this;
    }

    /**
     * Complete and create fueling if there are some saved partial fueling
     * @param LifecycleEventArgs $event
     * @throws \Doctrine\ORM\ORMException
     * @ORM\PrePersist
     */
    public function completeWithPartial(LifecycleEventArgs $event) {
        // Do nothing if it's a fueling created from a partial one
        if ($this->isFromPartial()) {
            return;
        }
        // There is no partial fueling : do nothing
        $partialFuelings = $event->getEntityManager()->getRepository(PartialFueling::class)->findByVehicle($this->getVehicle());
        if (empty($partialFuelings) ) {
            return;
        }
        $toCreate = [];
        $totalDistance = $this->getTraveledDistance();
        $totalVolume = $this->getVolume();
        foreach ($partialFuelings as $partialFueling) {
            $totalDistance += $partialFueling->getTraveledDistance();
            $totalVolume += $partialFueling->getVolume();
            $toCreate[] = static::getInstanceFromPartial($partialFueling);
            // Remove the partial fueling
            $event->getEntityManager()->remove($partialFueling);
        }
        $remainingDistance = $totalDistance;
        foreach ($toCreate as $fuelingToCreate) {
            // Calculate the estimated real traveled distance for the complete fueling created from a partial one
            $fuelingToCreate->setTraveledDistance(static::calculateRealTraveledDistance($fuelingToCreate->getVolume(), $totalDistance, $totalVolume));
            $remainingDistance -= $fuelingToCreate->getTraveledDistance();
            $event->getEntityManager()->persist($fuelingToCreate);
        }
        // The distance of the new fueling is changed to obtain the "real" consumption
        $this->setTraveledDistance($remainingDistance);
    }

    /**
     * Initialize a new fueling from a partial one
     * @param PartialFueling $partial
     * @return Fueling
     */
    private static function getInstanceFromPartial(PartialFueling $partial) {
        $fueling = new Fueling();
        $fueling->setDate($partial->getDate());
        $fueling->setAdditivedFuel($partial->isAdditivedFuel());
        $fueling->setShowedConsumption($partial->getShowedConsumption());
        $fueling->setAmount($partial->getAmount());
        $fueling->setVolumePrice($partial->getVolumePrice());
        $fueling->setVolume($partial->getVolume());
        $fueling->setFuelType($partial->getFuelType());
        $fueling->setVehicle($partial->getVehicle());
        $fueling->setFromPartial(true);
        return $fueling;
    }

    /**
     * Calculate the estimated real traveled distance
     * with a simply rule of three
     * We'll get the traveled distance to obtain the same average consumption
     * @param integer $volume
     * @param integer $totalTraveledDistance
     * @param integer $totalVolume
     * @return integer
     */
    private static function calculateRealTraveledDistance(int $volume, int $totalTraveledDistance, int $totalVolume) {
        return (int) round($volume * $totalTraveledDistance / $totalVolume);
    }
}
