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
use App\Tools\TimeCanvasPoint;
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
        $sSConsumptions = new TimeCanvasSerie($sLabel, $color->getRgba(0.5));
        $canvas->addSerie($sRConsumptions)->addSerie($sSConsumptions);
        foreach ($fuelings as $consumption) {
            $sRConsumptions->addPoint($this->fuelingBO->getRealConsumptionPoint($consumption));
            $sSConsumptions->addPoint($this->fuelingBO->getShowedConsumptionPoint($consumption));
        }
    }
}