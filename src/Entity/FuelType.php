<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FuelTypeRepository")
 */
class FuelType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name = '';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $color = '#000';

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return FuelType
     */
    public function setName(string $name): FuelType
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return FuelType
     */
    public function setColor(string $color): FuelType
    {
        $this->color = $color;
        return $this;
    }

}
