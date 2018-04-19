<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('my_account');
        }
        return $this->redirectToRoute('security_login');
    }

    /**
     * @Route("/account", name="my_account")
     */
    public function account()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $params = [
            'vehicles' => $this->getUser()->getVehicles()
        ];
        return $this->render('index/account.html.twig', $params);
    }
}
