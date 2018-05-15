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
     * @param VehicleBO $vehicleBO
     * @param TranslatorInterface $translator
     * @return Response
     * @Route("/account", name="my_account")
     * @throws \Exception
     */
    public function account(VehicleBO $vehicleBO, TranslatorInterface $translator)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $consumptionCanvas = TimeCanvas::getInstance()
            ->setName($translator->trans("Consumptions"))
            ->setYScaleLabel($translator->trans('Consumption %unit%', ['%unit%' => 'l/100km']));
        $comparativeCanvas = TimeCanvas::getInstance()
            ->setName($translator->trans("Comparative consumption"))
            ->setYScaleLabel($translator->trans('Consumption %unit%', ['%unit%' => 'l/100km']))
            ->addDisplayFormat('month', 'MMM')
            ->setTooltipFormat('DD/MM');

        $fuelingRepo = $this->getDoctrine()->getRepository(Fueling::class);
        $vehicles = $this->getUser()->getVehicles();
        foreach ($vehicles as $vehicle) {
            $vehicleBO->fillConsumptionCanvas($vehicle, $fuelingRepo->findByVehicleWithDateLimit($vehicle), $consumptionCanvas);
            $vehicleBO->fillComparativeCanvas($vehicle, $fuelingRepo->findByVehicleWithDateLimit($vehicle), $fuelingRepo->findPreviousYearByVehicle($vehicle), $comparativeCanvas);
        }
        $params = [
            'vehicles' => $vehicles,
            'canvasList' => [
                $consumptionCanvas,
                $comparativeCanvas
            ]
        ];
        return $this->render('index/account.html.twig', $params);
    }
}
