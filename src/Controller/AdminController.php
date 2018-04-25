<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $uRepo = $this->getDoctrine()->getRepository(User::class);
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'active_link' => 'admin',
            'users' => $uRepo->findAllPaginate()
        ]);
    }
}
