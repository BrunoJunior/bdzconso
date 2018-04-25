<?php

namespace App\Controller;

use App\Entity\Fueling;
use App\Entity\FuelType;
use App\Entity\Vehicle;
use App\Form\FuelingImportType;
use App\Form\FuelingType;
use App\Form\VehicleType;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\DateTime;

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
            'new' => true,
            'active_link' => 'my_account'
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
     * @return Response
     * @Route("/vehicle/{id}/fuelings/import", name="vehicle_fuelings_import",
     *     requirements={
     *         "id": "\d+"
     *     })
     *
     * @throws AccessDeniedException|\Doctrine\ORM\NonUniqueResultException
     */
    public function importFuelings(Request $request, Vehicle $vehicle, LoggerInterface $logger)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        if ($this->getUser()->getId() !== $vehicle->getUser()->getId()) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(FuelingImportType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $numberFormatter = new \NumberFormatter('fr_FR', \NumberFormatter::DECIMAL);
            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(FuelType::class);
            // Import du fichier
            $filename = $form['file']->getData()->getPathname();
            if (($handle = fopen($filename, "r")) !== FALSE) { // Lecture du fichier, à adapter
                while (($data = fgetcsv($handle, 1000)) !== FALSE) { // Eléments séparés par un point-virgule, à modifier si necessaire
                    if (count($data) < 7 || count($data) > 8) {
                        $logger->error("Bad CSV format!");
                        continue;
                    }
                    $data[0] = \DateTime::createFromFormat('d/m/Y', $data[0]);
                    $data[1] = $repository->findByName($data[1]);
                    for ($i = 2; $i < 7; $i++) {
                        $data[$i] = $numberFormatter->parse($data[$i]);
                        if (!is_float($data[$i])) {
                            $logger->error("Bad number format!");
                            continue;
                        }
                    }
                    if (!$data[0] instanceof \DateTime) {
                        $logger->error("Bad date format!");
                        continue;
                    }
                    if (!$data[1] instanceof FuelType) {
                        $logger->error("Unknown fuel type!");
                        continue;
                    }

                    $fueling = new Fueling();
                    $fueling->setVehicle($vehicle);
                    $fueling->setDate($data[0]);
                    $fueling->setFuelType($data[1]);
                    $fueling->setVolume((int) ($data[2] * 1000));
                    $fueling->setVolumePrice((int) ($data[3] * 1000));
                    $fueling->setAmount((int) ($data[4] * 100));
                    $fueling->setTraveledDistance((int) ($data[5] * 10));
                    $fueling->setShowedConsumption((int) ($data[6] * 10));
                    if (array_key_exists(7, $data)) {
                        $fueling->setAdditivedFuel((bool) $data[7]);
                    }
                    $em->persist($fueling);
                }
                fclose($handle);
                $em->flush();
            }
            return $this->redirectToRoute('vehicle_fuelings', [
                'id' => $vehicle->getId()
            ]);
        }

        return $this->render('vehicle/fuelings_import.html.twig', [
            'form' => $form->createView(),
            'active_link' => 'my_account'
        ]);
    }
}
