<?php

namespace App\Controller;

use App\Business\UserBO;
use App\Business\VehicleBO;
use App\Entity\Fueling;
use App\Entity\Vehicle;
use App\Tools\TimeCanvas;
use App\Tools\Tuile;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\TranslatorInterface;

class StatsController extends Controller
{
    /**
     * @Route("/stats", name="my_stats")
     */
    public function index(TranslatorInterface $translator, VehicleBO $vehicleBO, UserBO $userBO)
    {
        $rFueling = $this->getDoctrine()->getRepository(Fueling::class);
        $rVehicle = $this->getDoctrine()->getRepository(Vehicle::class);

        $tuiles = new ArrayCollection();
        $tuiles->add(Tuile::getInstance($translator->trans("Number of fueling"), $rFueling->countByUser($this->getUser())));
        $tuiles->add(Tuile::getInstance($translator->trans("Cumulated distance"), $rFueling->getTotalTraveledDistance($this->getUser()) . " km"));
        $tuiles->add(Tuile::getInstance($translator->trans("Vehicles"), $rVehicle->countByUser($this->getUser())));
        $tuiles->add(Tuile::getInstance($translator->trans("Total amount"), ($rFueling->getTotalAmount($this->getUser()) / 100) . '€'));
        $tuiles->add(Tuile::getInstance($translator->trans("Total fuel consumed"), ($rFueling->getTotalConsumedVolume($this->getUser()) / 10) . ' l'));

        $canvas = new ArrayCollection();
        $canvasConsumption = new TimeCanvas();
        $canvasConsumption->setName($translator->trans("Consumptions"))
            ->setYScaleLabel($translator->trans('Consumption %unit%', ['%unit%' => 'l/100km']));

        $canvasVolume = new TimeCanvas();
        $canvasVolume->setName($translator->trans("Amount"))
            ->setYScaleLabel('€');

        $canvasPrice = new TimeCanvas();
        $canvasPrice->setName($translator->trans("Price by fuel type"))
            ->setYScaleLabel('€ / l');
        $userBO->fillPriceVolumeCanvas($this->getUser(), $rFueling->findCurrentYearByUser($this->getUser()), $canvasPrice);

        $vehicles = $this->getUser()->getVehicles();
        foreach ($vehicles as $vehicle) {
            $fuelings = $rFueling->findCurrentYearByVehicle($vehicle);
            $vehicleBO->fillConsumptionCanvas($vehicle, $fuelings, $canvasConsumption);
            $vehicleBO->fillAmountCanvas($vehicle, $fuelings, $canvasVolume);
        }

        $canvas->add($canvasConsumption);
        $canvas->add($canvasPrice);
        $canvas->add($canvasVolume);

        return $this->render('stats/view.html.twig', [
            'canvas' => $canvas->toArray(),
            'tuiles' => $tuiles->toArray(),
        ]);
    }
}
