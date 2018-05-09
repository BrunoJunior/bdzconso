<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FuelingRepository")
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
     * Traveled distance in hectometers
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $traveledDistance;

    /**
     * Showed consumption in liter / hectometer
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $showedConsumption;


    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getTraveledDistance(): ?int
    {
        return $this->traveledDistance;
    }

    /**
     * @param int $traveledDistance
     * @return Fueling
     */
    public function setTraveledDistance(int $traveledDistance): Fueling
    {
        $this->traveledDistance = $traveledDistance;
        return $this;
    }

    /**
     * @return int
     */
    public function getShowedConsumption(): ?int
    {
        return $this->showedConsumption;
    }

    /**
     * @param int $showedConsumption
     * @return Fueling
     */
    public function setShowedConsumption(int $showedConsumption): Fueling
    {
        $this->showedConsumption = $showedConsumption;
        return $this;
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
}
