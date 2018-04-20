<?php

namespace App\Controller;

use App\Form\ConnectionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="security_login")
     */
    public function login(Request $request, AuthenticationUtils $helper): Response
    {
        $initialValues= ['_username' => $helper->getLastUsername()];
        $info = '';
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $initialValues['_username'] = $this->getUser()->getEmail();
            $initialValues['_remember_me'] = true;
            $info = 'You must specify your password to achieve this action!';
        }
        $form = $this->createForm(ConnectionType::class, $initialValues);
        $form->handleRequest($request);

        return $this->render(
            'security/login.html.twig',
            [
                'form' => $form->createView(),
                'error' => $helper->getLastAuthenticationError(),
                'info' => $info,
            ]
        );

        return $this->render('security/login.html.twig', [
            // dernier email saisi (si il y en a un)
            'last_email' => $helper->getLastUsername(),
            // La derniere erreur de connexion (si il y en a une)
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * La route pour se deconnecter.
     *
     * Mais celle ci ne doit jamais être executé car symfony l'interceptera avant.
     *
     *
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
}
