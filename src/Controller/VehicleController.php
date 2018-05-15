<?php

namespace App\Controller;

use App\Business\FuelingBO;
use App\Entity\Fueling;
use App\Entity\FuelType;
use App\Entity\PartialFueling;
use App\Entity\Vehicle;
use App\Form\AbstractFuelingType;
use App\Form\FuelingImportType;
use App\Form\FuelingType;
use App\Form\PartialFuelingType;
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
     * @Route("/vehicle/{id}", name="my_vehicle",
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
            'new' => $vehicle->getId() == 0,
            'active_link' => 'my_account'
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
     * @Route("/vehicle/{id}/refill", name="my_refill_vehicle",
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
        $date = new \DateTime();
        $fueling = new Fueling();
        $partialFueling = new PartialFueling();
        $fueling->setDate($date);
        $partialFueling->setDate($date);
        $preferedFuelType = $vehicle->getPreferredFuelType();
        if ($preferedFuelType instanceof FuelType) {
            $fueling->setFuelType($preferedFuelType);
            $partialFueling->setFuelType($preferedFuelType);
        }
        $form = $this->createForm(FuelingType::class, $fueling, [AbstractFuelingType::OPTION_CONSUMPTION_SHOWED => $vehicle->isConsumptionShowed()]);
        $formPartial = $this->createForm(PartialFuelingType::class, $partialFueling, [AbstractFuelingType::OPTION_CONSUMPTION_SHOWED => $vehicle->isConsumptionShowed()]);

        $form->handleRequest($request);
        $formPartial->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() || $formPartial->isSubmitted() && $formPartial->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($form->isSubmitted()) {
                $fueling->setVehicle($vehicle);
                $em->persist($fueling);
            } else {
                $partialFueling->setVehicle($vehicle);
                $em->persist($partialFueling);
            }
            // On enregistre le véhicule dans la base
            $em->flush();

            return $this->redirectToRoute('my_account');
        }

        return $this->render('fueling/edit.html.twig', [
            'form' => $form->createView(),
            'form_partial' => $formPartial->createView(),
            'new' => true,
            'active_link' => 'my_account'
        ]);
    }

    /**
     * @param Request $request
     * @param Vehicle $vehicle
     * @param int $page
     * @return Response
     * @Route("/vehicle/{id}/fuelings/{page}", name="my_vehicle_fuelings",
     *     requirements={
     *         "id": "\d+",
     *         "page": "\d+"
     *     })
     * @throws AccessDeniedException|\Doctrine\ORM\NonUniqueResultException
     */
    public function fuelings(Request $request, Vehicle $vehicle, int $page = 1) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $nbMax = 10;
        $repository = $this->getDoctrine()->getRepository(Fueling::class);
        $nbPages = (int) ceil($repository->countByVehicle($vehicle) / 10);
        return $this->render('vehicle/fuelings_list.html.twig', [
            'vehicleId' => $vehicle->getId(),
            'fuelings' => $repository->findByVehicle($vehicle, $page, $nbMax),
            'page' => $page,
            'nbPages' => ($nbPages ? $nbPages : 1),
            'active_link' => 'my_account'
        ]);
    }

    /**
     * @param Request $request
     * @param Vehicle $vehicle
     * @param FuelingBO $fueling
     * @return Response
     * @Route("/vehicle/{id}/fuelings/import", name="my_vehicle_fuelings_import",
     *     requirements={
     *         "id": "\d+"
     *     })
     *
     * @throws AccessDeniedException|\Doctrine\ORM\NonUniqueResultException
     */
    public function importFuelings(Request $request, Vehicle $vehicle, FuelingBO $fueling)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        if ($this->getUser()->getId() !== $vehicle->getUser()->getId()) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(FuelingImportType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fueling->import($form['file']->getData()->getPathname(), $vehicle);
            return $this->redirectToRoute('my_vehicle_fuelings', [
                'id' => $vehicle->getId()
            ]);
        }

        return $this->render('vehicle/fuelings_import.html.twig', [
            'form' => $form->createView(),
            'active_link' => 'my_account'
        ]);
    }
}
