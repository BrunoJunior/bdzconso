<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 09/05/18
 * Time: 22:59
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\MappedSuperclass
 */
class SuperFueling
{
    /**
     * @var Vehicle
     * @ORM\ManyToOne(targetEntity="App\Entity\Vehicle", inversedBy="fuelings")
     */
    protected $vehicle;

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     * @Assert\Date()
     * @Assert\LessThanOrEqual("today")
     */
    protected $date;

    /**
     * @var FuelType
     * @ORM\ManyToOne(targetEntity="App\Entity\FuelType")
     */
    protected $fuelType;

    /**
     * Volume in milliliter
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $volume;

    /**
     * Volume price in tenth of cents
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $volumePrice;

    /**
     * Amount in cents
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $amount;

    /**
     * Additived fuel
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $additivedFuel = false;

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

    /**
     * @return Vehicle
     */
    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    /**
     * @param Vehicle $vehicle
     * @return SuperFueling
     */
    public function setVehicle(Vehicle $vehicle): SuperFueling
    {
        $this->vehicle = $vehicle;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return SuperFueling
     */
    public function setDate(\DateTime $date): SuperFueling
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return FuelType
     */
    public function getFuelType(): ?FuelType
    {
        return $this->fuelType;
    }

    /**
     * @param FuelType $fuelType
     * @return SuperFueling
     */
    public function setFuelType(FuelType $fuelType): SuperFueling
    {
        $this->fuelType = $fuelType;
        return $this;
    }

    /**
     * Volume in milliliter
     * @return int
     */
    public function getVolume(): ?int
    {
        return $this->volume;
    }

    /**
     * @param int $volume
     * @return SuperFueling
     */
    public function setVolume(int $volume): SuperFueling
    {
        $this->volume = $volume;
        return $this;
    }

    /**
     * @return int
     */
    public function getVolumePrice(): ?int
    {
        return $this->volumePrice;
    }

    /**
     * @param int $volumePrice
     * @return SuperFueling
     */
    public function setVolumePrice(int $volumePrice): SuperFueling
    {
        $this->volumePrice = $volumePrice;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return SuperFueling
     */
    public function setAmount(int $amount): SuperFueling
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAdditivedFuel(): bool
    {
        return $this->additivedFuel;
    }

    /**
     * @param bool $additivedFuel
     * @return SuperFueling
     */
    public function setAdditivedFuel(bool $additivedFuel): SuperFueling
    {
        $this->additivedFuel = $additivedFuel;
        return $this;
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
     * @return SuperFueling
     */
    public function setTraveledDistance(int $traveledDistance): SuperFueling
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
     * @return SuperFueling
     */
    public function setShowedConsumption(int $showedConsumption): SuperFueling
    {
        $this->showedConsumption = $showedConsumption;
        return $this;
    }
}