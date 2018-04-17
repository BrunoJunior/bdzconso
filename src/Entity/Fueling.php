<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FuelingRepository")
 */
class Fueling
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Vehicle
     * @ORM\ManyToOne(targetEntity="App\Entity\Vehicle", inversedBy="fuelings")
     */
    private $vehicle;

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @var FuelType
     * @ORM\ManyToOne(targetEntity="App\Entity\FuelType")
     */
    private $fuelType;

    /**
     * Volume in milliliter
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $volume;

    /**
     * Volume price in tenth of cents
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $volumePrice;

    /**
     * Amount in cents
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * Traveled distance in hectometers
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $traveledDistance;

    /**
     * Showed consumption in liter / hectometer
     * @var integer
     */
    private $showedConsumption;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Vehicle
     */
    public function getVehicle(): Vehicle
    {
        return $this->vehicle;
    }

    /**
     * @param Vehicle $vehicle
     * @return Fueling
     */
    public function setVehicle(Vehicle $vehicle): Fueling
    {
        $this->vehicle = $vehicle;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Fueling
     */
    public function setDate(\DateTime $date): Fueling
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return FuelType
     */
    public function getFuelType(): FuelType
    {
        return $this->fuelType;
    }

    /**
     * @param FuelType $fuelType
     * @return Fueling
     */
    public function setFuelType(FuelType $fuelType): Fueling
    {
        $this->fuelType = $fuelType;
        return $this;
    }

    /**
     * Volume in milliliter
     * @return int
     */
    public function getVolume(): int
    {
        return $this->volume;
    }

    /**
     * @param int $volume
     * @return Fueling
     */
    public function setVolume(int $volume): Fueling
    {
        $this->volume = $volume;
        return $this;
    }

    /**
     * @return int
     */
    public function getVolumePrice(): int
    {
        return $this->volumePrice;
    }

    /**
     * @param int $volumePrice
     * @return Fueling
     */
    public function setVolumePrice(int $volumePrice): Fueling
    {
        $this->volumePrice = $volumePrice;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return Fueling
     */
    public function setAmount(int $amount): Fueling
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return int
     */
    public function getTraveledDistance(): int
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
    public function getShowedConsumption(): int
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
}
