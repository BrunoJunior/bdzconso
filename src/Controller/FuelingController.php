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
     * @Route("/fueling/{id}", name="my_fueling",
     *     requirements={
     *         "id": "\d+"
     *     })
     */
    public function edit(Request $request, Fueling $fueling)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $form = $this->createForm(FuelingType::class, $fueling);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fueling);
            $em->flush();
            return $this->redirectToRoute('my_vehicle_fuelings', ['id' => $fueling->getVehicle()->getId()]);
        }

        return $this->render('fueling/edit.html.twig', [
            'form' => $form->createView(),
            'new' => false,
            'defaultFuelType' => $fueling->getFuelType(),
            'active_link' => 'my_account'
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
        return $this->redirectToRoute('my_vehicle_fuelings', ['id' => $fueling->getVehicle()->getId()]);
    }

    /**
     * @param Request $request
     * @param int $page
     * @return Response
     * @Route("/fueling/all/{page}", name="my_fuelings",
     *     requirements={
     *         "page": "\d+"
     *     })
     * @throws AccessDeniedException|\Doctrine\ORM\NonUniqueResultException
     */
    public function list(Request $request, int $page = 1) {
        $nbMax = 10;
        $repository = $this->getDoctrine()->getRepository(Fueling::class);
        $nbPages = (int) ceil($repository->countByUser($this->getUser()) / 10);
        return $this->render('fueling/list.html.twig', [
            'fuelings' => $repository->findByUser($this->getUser(), $page, $nbMax),
            'page' => $page,
            'nbPages' => ($nbPages ? $nbPages : 1),
            'active_link' => 'my_fuelings'
        ]);
    }
}
