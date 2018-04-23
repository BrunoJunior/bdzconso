<?php

namespace App\Controller;

use App\Entity\Fueling;
use App\Entity\FuelType;
use App\Entity\Vehicle;
use App\Form\FuelingType;
use App\Form\VehicleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VehicleController extends Controller
{
    /**
     * @param Request $request
     * @param Vehicle $vehicle
     * @return Response
     * @Route("/vehicle/{id}", name="vehicle",
     *     defaults={"id"= 0},
     *     requirements={
     *         "id": "\d+"
     *     })
     */
    public function edit(Request $request, Vehicle $vehicle = null)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        if ($vehicle === null) {
            $vehicle = new Vehicle();
        }
        $form = $this->createForm(VehicleType::class, $vehicle);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $vehicle->setUser($this->getUser());
            // On enregistre le véhicule dans la base
            $em = $this->getDoctrine()->getManager();
            $em->persist($vehicle);
            $em->flush();

            return $this->redirectToRoute('my_account');
        }

        return $this->render('vehicle/edit.html.twig', [
            'form' => $form->createView(),
            'new' => $vehicle->getId() == 0
        ]);
    }

    /**
     * @param Vehicle $vehicle
     * @return Response
     * @Route("/vehicle/{id}/delete", name="delete_vehicle",
     *     requirements={
     *         "id": "\d+"
     *     })
     * @throws AccessDeniedException
     */
    public function delete(Vehicle $vehicle) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->getUser()->getId() !== $vehicle->getUser()->getId()) {
            throw $this->createAccessDeniedException();
        }
        // Delete vehicle
        $em = $this->getDoctrine()->getManager();
        $em->remove($vehicle);
        $em->flush();
        return $this->redirectToRoute('my_account');
    }

    /**
     * @param Request $request
     * @param Vehicle $vehicle
     * @return Response
     * @Route("/vehicle/{id}/refill", name="refill_vehicle",
     *     requirements={
     *         "id": "\d+"
     *     })
     * @throws AccessDeniedException
     */
    public function refill(Request $request, Vehicle $vehicle)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        if ($this->getUser()->getId() !== $vehicle->getUser()->getId()) {
            throw $this->createAccessDeniedException();
        }
        $fueling = new Fueling();
        $fueling->setDate(new \DateTime());
        $preferedFuelType = $vehicle->getPreferredFuelType();
        if ($preferedFuelType instanceof FuelType) {
            $fueling->setFuelType($preferedFuelType);
        }
        $form = $this->createForm(FuelingType::class, $fueling);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fueling->setVehicle($vehicle);
            // On enregistre le véhicule dans la base
            $em = $this->getDoctrine()->getManager();
            $em->persist($fueling);
            $em->flush();

            return $this->redirectToRoute('my_account');
        }

        return $this->render('fueling/edit.html.twig', [
            'form' => $form->createView(),
            'new' => true
        ]);
    }

    /**
     * @param Request $request
     * @param Vehicle $vehicle
     * @param int $page
     * @return Response
     * @Route("/vehicle/{id}/fuelings/{page}", name="vehicle_fuelings",
     *     requirements={
     *         "id": "\d+",
     *         "page": "\d+"
     *     })
     * @throws AccessDeniedException|\Doctrine\ORM\NonUniqueResultException
     */
    public function fuelings(Request $request, Vehicle $vehicle, int $page = 1) {
        $nbMax = 10;
        $repository = $this->getDoctrine()->getRepository(Fueling::class);
        $nbPages = (int) ceil(count($repository->countByVehicle($vehicle)) / 10);
        return $this->render('vehicle/fuelings_list.html.twig', [
            'vehicleId' => $vehicle->getId(),
            'fuelings' => $repository->findByVehicle($vehicle, $page, $nbMax),
            'page' => $page,
            'nbPages' => $nbPages
        ]);
    }
}
