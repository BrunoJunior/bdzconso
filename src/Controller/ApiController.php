<?php

namespace App\Controller;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ApiController
 * @package App\Controller
 */
abstract class ApiController extends Controller
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ApiController constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @return SerializerInterface
     */
    protected function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    /**
     * @param $data
     * @param int $status
     * @param array $headers
     * @param array $context
     * @return JsonResponse
     */
    protected function json($data, int $status = 200, array $headers = array(), array $context = array()): JsonResponse
    {
        $json = $this->serializer->serialize($data, 'json');
        return new JsonResponse($json, $status, $headers, true);
    }
}
