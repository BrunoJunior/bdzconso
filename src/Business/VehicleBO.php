<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 04/05/18
 * Time: 00:06
 */

namespace App\Business;


use App\Entity\Vehicle;
use App\Tools\Color;
use App\Tools\TimeCanvas;
use App\Tools\TimeCanvasSerie;
use Symfony\Component\Translation\TranslatorInterface;

class VehicleBO
{
    /**
     * @var FuelingBO
     */
    private $fuelingBO;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * VehicleBO constructor.
     * @param FuelingBO $fuelingBO
     * @param TranslatorInterface $translator
     */
    public function __construct(FuelingBO $fuelingBO, TranslatorInterface $translator)
    {
        $this->fuelingBO = $fuelingBO;
        $this->translator = $translator;
    }

    /**
     * @param Vehicle $vehicle
     * @param array $fuelings
     * @param TimeCanvas $canvas
     */
    public function fillConsumptionCanvas(Vehicle $vehicle, array $fuelings, TimeCanvas $canvas) {
        $rLabel = $vehicle->getManufacturer() . ' ' . $vehicle->getModel();
        $sLabel = $rLabel . ' - ' . $this->translator->trans('Showed');
        $color = new Color($vehicle->getColor());
        $sRConsumptions = new TimeCanvasSerie($rLabel, $color->getRgba());
        $canvas->addSerie($sRConsumptions);
        if ($vehicle->isConsumptionShowed()) {
            $sSConsumptions = new TimeCanvasSerie($sLabel, $color->getRgba(0.5));
            $canvas->addSerie($sSConsumptions);
        }
        foreach ($fuelings as $consumption) {
            $sRConsumptions->addPoint($this->fuelingBO->getRealConsumptionPoint($consumption));
            if (isset($sSConsumptions)) {
                $sSConsumptions->addPoint($this->fuelingBO->getShowedConsumptionPoint($consumption));
            }
        }
    }

    /**
     * @param Vehicle $vehicle
     * @param array $fuelings
     * @param array $fuelingsPrevYear
     * @param TimeCanvas $canvas
     * @throws \Exception
     */
    public function fillComparativeCanvas(Vehicle $vehicle, array $fuelings, array $fuelingsPrevYear, TimeCanvas $canvas) {
        $rLabel = $vehicle->getManufacturer() . ' ' . $vehicle->getModel();
        $sLabel = $vehicle->getManufacturer() . ' ' . $vehicle->getModel() . ' - ' . $this->translator->trans('Previous year');
        $color = new Color($vehicle->getColor());
        $sConsumption = new TimeCanvasSerie($rLabel, $color->getRgba());
        $sPrevYearConsumption = new TimeCanvasSerie($sLabel, $color->getRgba(0.5));
        $canvas->addSerie($sConsumption);
        $canvas->addSerie($sPrevYearConsumption);
        foreach ($fuelings as $consumption) {
            $point = $this->fuelingBO->getRealConsumptionPoint($consumption);
            $point->getDate()->sub(new \DateInterval('P1Y'));
            $sConsumption->addPoint($point);
        }
        foreach ($fuelingsPrevYear as $consumption) {
            $sPrevYearConsumption->addPoint($this->fuelingBO->getRealConsumptionPoint($consumption));
        }
    }

    /**
     * @param Vehicle $vehicle
     * @param array $fuelings
     * @param TimeCanvas $canvas
     */
    public function fillAmountCanvas(Vehicle $vehicle, array $fuelings, TimeCanvas $canvas) {
        $rLabel = $vehicle->getManufacturer() . ' ' . $vehicle->getModel();
        $color = new Color($vehicle->getColor());
        $serie = new TimeCanvasSerie($rLabel, $color->getRgba());
        $canvas->addSerie($serie);
        foreach ($fuelings as $fueling) {
            $serie->addPoint($this->fuelingBO->getAmountPoint($fueling));
        }
    }
}