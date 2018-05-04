<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 04/05/18
 * Time: 22:05
 */

namespace App\Business;


use App\Entity\Fueling;
use App\Entity\User;
use App\Repository\FuelTypeRepository;
use App\Tools\Color;
use App\Tools\TimeCanvas;
use App\Tools\TimeCanvasSerie;

class UserBO
{

    /**
     * @var FuelTypeRepository
     */
    private $rFuelType;

    /**
     * @var FuelingBO
     */
    private $fuelingBO;

    /**
     * UserBO constructor.
     * @param FuelTypeRepository $rFuelType
     */
    public function __construct(FuelTypeRepository $rFuelType, FuelingBO $fuelingBO)
    {
        $this->rFuelType = $rFuelType;
        $this->fuelingBO = $fuelingBO;
    }

    /**
     * @param User $user
     * @param Fueling[] $fuelings
     * @param TimeCanvas $canvas
     */
    public function fillPriceVolumeCanvas(User $user, array $fuelings, TimeCanvas $canvas) {

        $fuelTypes = $this->rFuelType->findAll();
        $seriesByFuelType = [];
        foreach ($fuelTypes as $fuelType) {
            $color = new Color($fuelType->getColor());
            $serie = new TimeCanvasSerie($fuelType->getName(), $color->getRgba());
            $canvas->addSerie($serie);
            $seriesByFuelType[$fuelType->getName()] = $serie;
        }
        foreach ($fuelings as $consumption) {
            $serie = $seriesByFuelType[$consumption->getFuelType()->getName()];
            $serie->addPoint($this->fuelingBO->getVolumePricePoint($consumption));
        }
    }
}