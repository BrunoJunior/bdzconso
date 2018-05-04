<?php

namespace App\Controller;

use App\Business\VehicleBO;
use App\Entity\Fueling;
use App\Tools\TimeCanvas;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\TranslatorInterface;

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
     * @param TimeCanvas $canvas
     * @param VehicleBO $vehicleBO
     * @param TranslatorInterface $translator
     * @return Response
     * @Route("/account", name="my_account")
     */
    public function account(TimeCanvas $canvas, VehicleBO $vehicleBO, TranslatorInterface $translator)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $canvas
            ->setName($translator->trans("Consumptions"))
            ->setYScaleLabel($translator->trans('Consumption %unit%', ['%unit%' => 'l/100km']));
        $fuelingRepo = $this->getDoctrine()->getRepository(Fueling::class);
        $vehicles = $this->getUser()->getVehicles();
        foreach ($vehicles as $vehicle) {
            $vehicleBO->fillConsumptionCanvas($vehicle, $fuelingRepo->findCurrentYearByVehicle($vehicle), $canvas);
        }
        $params = [
            'vehicles' => $vehicles,
            'canvas' => $canvas
        ];
        return $this->render('index/account.html.twig', $params);
    }
}
