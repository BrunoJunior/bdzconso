<?php

namespace App\Controller;

use App\Entity\Fueling;
use App\Form\FuelingType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FuelingController extends Controller
{
    /**
     * @param Request $request
     * @param Fueling $fueling
     * @return Response
     * @Route("/fueling/{id}", name="fueling",
     *     requirements={
     *         "id": "\d+"
     *     })
     */
    public function edit(Request $request, Fueling $fueling)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(FuelingType::class, $fueling);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fueling);
            $em->flush();
            return $this->redirectToRoute('vehicle_fuelings', ['id' => $fueling->getVehicle()->getId()]);
        }

        return $this->render('fueling/edit.html.twig', [
            'form' => $form->createView(),
            'new' => false,
            'defaultFuelType' => $fueling->getFuelType()
        ]);
    }

    /**
     * @param Fueling $fueling
     * @return Response
     * @Route("/fueling/{id}/delete", name="delete_fueling",
     *     requirements={
     *         "id": "\d+"
     *     })
     * @throws AccessDeniedException
     */
    public function delete(Fueling $fueling) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->getUser()->getId() !== $fueling->getVehicle()->getUser()->getId()) {
            throw $this->createAccessDeniedException();
        }
        // Delete fueling
        $em = $this->getDoctrine()->getManager();
        $em->remove($fueling);
        $em->flush();
        return $this->redirectToRoute('vehicle_fuelings', ['id' => $fueling->getVehicle()->getId()]);
    }
}
