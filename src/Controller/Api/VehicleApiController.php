<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 21/05/18
 * Time: 15:08
 */

namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\Vehicle;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class VehicleApiController
 * @package App\Controller\Api
 * @Route("/api/vehicles")
 */
class VehicleApiController extends ApiController
{
    /**
     * @return JsonResponse
     * @Route("/", methods={"GET"})
     */
    public function vehiclesList(): JsonResponse
    {
        return $this->json($this->getUser()->getVehicles());
    }

    /**
     * @param Vehicle $vehicle
     * @return JsonResponse
     * @Route("/{id}", methods={"GET"},
     *     requirements={
     *         "id": "\d+"
     *     })
     */
    public function vehicle(Vehicle $vehicle): JsonResponse
    {
        return $this->json($vehicle);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/", methods={"POST"})
     */
    public function newVehicle(Request $request): Response
    {
        $vehicle = $this->getSerializer()->deserialize($request->getContent(), Vehicle::class, 'json');
        $vehicle->setUser($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($vehicle);
        $em->flush();
        return $this->json($vehicle, JsonResponse::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateVehicle(Request $request): Response
    {

    }
}