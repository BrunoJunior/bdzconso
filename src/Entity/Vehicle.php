<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Fueling", mappedBy="vehicle")
     */
    private $fuelings;

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
    public function getManufacturer(): string
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
    public function getModel(): string
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
    public function getYear(): int
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
    public function getUser(): User
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

}
