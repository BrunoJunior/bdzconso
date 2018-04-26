<?php

namespace App\Controller;

use App\Entity\Fueling;
use App\Entity\FuelType;
use App\Entity\User;
use App\Entity\Vehicle;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\TranslatorInterface;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(TranslatorInterface $translator)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $uRepo = $this->getDoctrine()->getRepository(User::class);
        $fRepo = $this->getDoctrine()->getRepository(Fueling::class);
        $vRepo = $this->getDoctrine()->getRepository(Vehicle::class);
        $ftRepo = $this->getDoctrine()->getRepository(FuelType::class);
        $stats = [
            [
                'title' => $translator->trans('Users'),
                'number' => $uRepo->count([]),
                'action' => 'admin_users'
            ],
            [
                'title' => $translator->trans('Fuelings'),
                'number' => $fRepo->count([])
            ],
            [
                'title' => $translator->trans('Vehicles'),
                'number' => $vRepo->count([])
            ],
            [
                'title' => $translator->trans('Fuel types'),
                'number' => $ftRepo->count([]),
                'action' => 'admin_fuel_types'
            ],
            [
                'title' => $translator->trans('Cumulated distance'),
                'number' => "" . ($fRepo->getTotalTraveledDistance() / 10) . " km"
            ]
        ];
        return $this->render('admin/index.html.twig', ['stats' => $stats]);
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
        return $this->render('admin/users.html.twig', [
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
        return $this->render('admin/fuel_types.html.twig', [
            'fuel_types' => $repo->findAll()
        ]);
    }
}
