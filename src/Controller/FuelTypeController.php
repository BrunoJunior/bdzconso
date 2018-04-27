<?php

namespace App\Controller;

use App\Entity\FuelType;
use App\Form\FuelTypeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FuelTypeController extends Controller
{
    /**
     * @Route("/fuel_type/{id}", name="admin_fuel_type",
     *     defaults={"id"= 0},
     *     requirements={
     *         "id": "\d+"
     *     })
     */
    public function edit(Request $request, FuelType $fuelType = null)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($fuelType === null) {
            $fuelType = new FuelType();
        }
        $form = $this->createForm(FuelTypeType::class, $fuelType);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // On enregistre l'utilisateur dans la base
            $em = $this->getDoctrine()->getManager();
            $em->persist($fuelType);
            $em->flush();
            return $this->redirectToRoute('admin_fuel_types');
        }

        return $this->render(
            'fuel_type/edit.html.twig',
            ['form' => $form->createView(), 'new' => $fuelType->getId() == 0]
        );
    }

    /**
     * @Route("/fuel_type/{id}/delete", name="delete_fuel_type")
     */
    public function delete(FuelType $fuelType)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $em = $this->getDoctrine()->getManager();
        $em->remove($fuelType);
        $em->flush();
        return $this->redirectToRoute('admin_fuel_types');
    }
}
