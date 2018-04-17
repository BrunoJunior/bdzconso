<?php

namespace App\Controller;

use App\Entity\Fueling;
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
     * @Route("/vehicle/{id}", name="vehicle", defaults={"id"= 0})
     */
    public function show(Request $request, Vehicle $vehicle = null)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
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

        return $this->render('vehicle/show.html.twig', [
            'form' => $form->createView(),
            'new' => $vehicle->getId() == 0
        ]);
    }

    /**
     * @param Vehicle $vehicle
     * @return Response
     * @Route("/vehicle/{id}/delete", name="delete_vehicle")
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
     * @Route("/vehicle/{id}/refill", name="refill_vehicle")
     * @throws AccessDeniedException
     */
    public function refill(Request $request, Vehicle $vehicle) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->getUser()->getId() !== $vehicle->getUser()->getId()) {
            throw $this->createAccessDeniedException();
        }
        $fueling = new Fueling();
        $fueling->setDate(new \DateTime());
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

        return $this->render('vehicle/fueling.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
