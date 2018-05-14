<?php

namespace App\Entity;

use App\Validator\VehicleValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VehicleRepository")
 */
class Vehicle
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $manufacturer;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $model;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User",inversedBy="vehicles")
     * @ORM\JoinColumn()
     */
    private $user;

    /**
     * @var FuelType[]|Collection
     * @ORM\ManyToMany(targetEntity="App\Entity\FuelType")
     * @ORM\JoinTable(name="compatible_fuels",
     *      joinColumns={@ORM\JoinColumn(name="vehicle_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="fuel_type_id", referencedColumnName="id")}
     *      )
     */
    private $compatibleFuels;

    /**
     * @var Fueling[]|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Fueling", mappedBy="vehicle", cascade={"remove"})
     */
    private $fuelings;

    /**
     * @var FuelType
     * @ORM\ManyToOne(targetEntity="App\Entity\FuelType")
     * @ORM\JoinColumn(name="preferred_fuel_type_id", referencedColumnName="id", nullable=true)
     */
    private $preferredFuelType;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $color;

    /**
     * @var PartialFueling[]|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\PartialFueling", mappedBy="vehicle", cascade={"remove"})
     */
    private $partialFuelings;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default" : 1})
     */
    private $consumptionShowed = true;

    /**
     * Vehicle constructor.
     */
    public function __construct() {
        $this->compatibleFuels = new ArrayCollection();
        $this->fuelings = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    /**
     * @param string $manufacturer
     * @return Vehicle
     */
    public function setManufacturer(string $manufacturer): Vehicle
    {
        $this->manufacturer = $manufacturer;
        return $this;
    }

    /**
     * @return string
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * @param string $model
     * @return Vehicle
     */
    public function setModel(string $model): Vehicle
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return int
     */
    public function getYear(): ?int
    {
        return $this->year;
    }

    /**
     * @param int $year
     * @return Vehicle
     */
    public function setYear(int $year): Vehicle
    {
        $this->year = $year;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Vehicle
     */
    public function setUser(User $user): Vehicle
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return FuelType[]|Collection
     */
    public function getCompatibleFuels(): Collection
    {
        return $this->compatibleFuels;
    }

    /**
     * @param FuelType $fuelType
     * @return Vehicle
     */
    public function addCompatibleFuel(FuelType $fuelType): Vehicle
    {
        if (!$this->compatibleFuels->contains($fuelType)) {
            $this->compatibleFuels[] = $fuelType;
        }
        return $this;
    }

    /**
     * @return Fueling[]|Collection
     */
    public function getFuelings(): Collection
    {
        return $this->fuelings;
    }

    /**
     * @return PartialFueling[]|Collection
     */
    public function getPartialFuelings(): Collection
    {
        return $this->partialFuelings;
    }

    /**
     * The average consumption in l/100km
     * @return float|null
     */
    public function getAvgConsumption(): ?float
    {
        $volume = $distance = 0;
        foreach ($this->getFuelings() as $fueling) {
            $volume += $fueling->getVolume(); // in ml (0.001 l)
            $distance += $fueling->getTraveledDistance(); // in hm (0.1 km)
        }
        if ($distance === 0) {
            return null;
        }
        // waited l/100km
        return $volume / $distance;
    }

    /**
     * The total traveled distance for the vehicle
     * @return float
     */
    public function getTotalTraveledDistance(): float {
        $distance = 0;
        foreach ($this->getFuelings() as $fueling) {
            $distance += $fueling->getTraveledDistance(); // in hm (0.1 km)
        }
        // Waited km
        return $distance / 10;
    }

    /**
     * @return FuelType
     */
    public function getPreferredFuelType(): ?FuelType
    {
        return $this->preferredFuelType;
    }

    /**
     * @param FuelType $preferredFuelType
     * @return Vehicle
     */
    public function setPreferredFuelType(FuelType $preferredFuelType = null): Vehicle
    {
        $this->preferredFuelType = $preferredFuelType;
        return $this;
    }

    /**
     * Vehicle validation
     * @param ExecutionContextInterface $context
     * @param $payload
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        VehicleValidator::validate($this, $context, $payload);
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string|null $color
     * @return Vehicle
     */
    public function setColor(?string $color): Vehicle
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return bool
     */
    public function isConsumptionShowed(): bool
    {
        return $this->consumptionShowed;
    }

    /**
     * @param bool $consumptionShowed
     * @return Vehicle
     */
    public function setConsumptionShowed(bool $consumptionShowed): Vehicle
    {
        $this->consumptionShowed = $consumptionShowed;
        return $this;
    }

    /**
     * The waiting traveled distance for the vehicle
     * @return float
     */
    public function getWaitingTraveledDistance(): float {
        $distance = 0;
        foreach ($this->getPartialFuelings() as $fueling) {
            $distance += $fueling->getTraveledDistance(); // in hm (0.1 km)
        }
        // Waited km
        return $distance / 10;
    }

}
