<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 04/05/18
 * Time: 23:10
 */

namespace App\Business;


use App\Entity\FuelType;
use App\Tools\Color;
use App\Tools\TimeCanvas;
use App\Tools\TimeCanvasPoint;
use App\Tools\TimeCanvasSerie;

class FuelTypeBO
{
    /**
     * @param FuelType $fuelType
     * @param array $rows
     * @param TimeCanvas $canvas
     */
    public function fillPriceVolumeCanvas(FuelType $fuelType, array $rows, TimeCanvas $canvas) {
        $color = new Color($fuelType->getColor());
        $serie = new TimeCanvasSerie($fuelType->getName(), $color->getRgba());
        $canvas->addSerie($serie);
        foreach ($rows as $row) {
            $serie->addPoint(new TimeCanvasPoint($row['date'], round($row['volumePrice'] / 1000.0, 3)));
        }
    }
}