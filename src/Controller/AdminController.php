<?php

namespace App\Controller;

use App\Business\FuelTypeBO;
use App\Entity\Fueling;
use App\Entity\FuelType;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Tools\TimeCanvas;
use App\Tools\Tuile;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\TranslatorInterface;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(TranslatorInterface $translator, FuelTypeBO $fuelTypeBO)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $uRepo = $this->getDoctrine()->getRepository(User::class);
        $fRepo = $this->getDoctrine()->getRepository(Fueling::class);
        $vRepo = $this->getDoctrine()->getRepository(Vehicle::class);
        $ftRepo = $this->getDoctrine()->getRepository(FuelType::class);

        $stats = new ArrayCollection();
        $stats->add(Tuile::getInstance($translator->trans('Users'), $uRepo->count([]))->setAction('admin_users'));
        $stats->add(Tuile::getInstance($translator->trans('Fuelings'), $fRepo->count([])));
        $stats->add(Tuile::getInstance($translator->trans('Vehicles'), $vRepo->count([])));
        $stats->add(Tuile::getInstance($translator->trans('Fuel types'), $ftRepo->count([]))->setAction('admin_fuel_types'));
        $stats->add(Tuile::getInstance($translator->trans('Cumulated distance'), "" . ($fRepo->getTotalTraveledDistance() / 10) . " km"));

        $canvas = new ArrayCollection();
        $canvasPrice = new TimeCanvas();
        $canvasPrice->setName($translator->trans("Fuel type price"))
            ->setYScaleLabel('€ / l');
        foreach ($ftRepo->findAll() as $fuelType) {
            $rows = $fRepo->getAverageVolumePrices($fuelType);
            $fuelTypeBO->fillPriceVolumeCanvas($fuelType, $rows, $canvasPrice);
        }
        $canvas->add($canvasPrice);

        return $this->render('admin/index.html.twig', ['stats' => $stats->toArray(), 'canvas' => $canvas->toArray()]);
    }

    /**
     * @Route("/admin/users/{page}", name="admin_users",
     *     requirements={
     *         "page": "\d+"
     *     })
     */
    public function users(int $page = 1)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $uRepo = $this->getDoctrine()->getRepository(User::class);
        $nbPages = (int) ceil($uRepo->count([]) / 10);
        return $this->render('user/list.html.twig', [
            'users' => $uRepo->findAllPaginate($page, 10),
            'page' => $page,
            'nbPages' => $nbPages
        ]);
    }

    /**
     * @Route("/admin/fuel_types", name="admin_fuel_types")
     */
    public function fuelTypes()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $repo = $this->getDoctrine()->getRepository(FuelType::class);
        return $this->render('fuel_type/list.html.twig', [
            'fuel_types' => $repo->findAll()
        ]);
    }
}
