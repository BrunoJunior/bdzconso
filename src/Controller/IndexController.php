<?php

namespace App\Controller;

use App\Entity\Fueling;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('my_account');
        }
        return $this->redirectToRoute('security_login');
    }

    /**
     * @Route("/account", name="my_account")
     */
    public function account()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $fuelingRepo = $this->getDoctrine()->getRepository(Fueling::class);
        $vehicles = $this->getUser()->getVehicles();
        $vehiclesConsumptions = [];
        $vehiclesCConsumptions = [];
        foreach ($vehicles as $vehicle) {
            $consumptions = [];
            $cConsumptions = [];
            foreach ($fuelingRepo->findCurrentYearByVehicle($vehicle) as $consumption) {
                $consumptions[] = ['x' => $consumption->getDate()->format('d/m/Y'), 'y' => round($consumption->getRealConsumption(), 2)];
                $cConsumptions[] = ['x' => $consumption->getDate()->format('d/m/Y'), 'y' => round($consumption->getShowedConsumption() / 10, 2)];
            }
            $vehiclesConsumptions[$vehicle->getId()] = json_encode($consumptions);
            $vehiclesCConsumptions[$vehicle->getId()] = json_encode($cConsumptions);
        }
        $params = [
            'vehicles' => $vehicles,
            'vehicles_consumptions' => $vehiclesConsumptions,
            'vehicles_calc_consumptions' => $vehiclesCConsumptions
        ];
        return $this->render('index/account.html.twig', $params);
    }
}
