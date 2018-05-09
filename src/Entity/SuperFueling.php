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
     * @return Vehicle
     */
    public function getVehicle(): ?Vehicle
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
    public function getDate(): ?\DateTime
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
    public function getFuelType(): ?FuelType
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
    public function getVolume(): ?int
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
    public function getVolumePrice(): ?int
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
    public function getAmount(): ?int
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
     * @return bool
     */
    public function isAdditivedFuel(): bool
    {
        return $this->additivedFuel;
    }

    /**
     * @param bool $additivedFuel
     */
    public function setAdditivedFuel(bool $additivedFuel): void
    {
        $this->additivedFuel = $additivedFuel;
    }
}