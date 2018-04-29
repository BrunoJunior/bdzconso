<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StatsController extends Controller
{
    /**
     * @Route("/stats", name="my_stats")
     */
    public function index()
    {
        return $this->render('stats/view.html.twig', [
            'controller_name' => 'StatsController',
        ]);
    }
}
