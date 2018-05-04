<?php

namespace App\Controller;

use App\Entity\Token;
use App\Entity\User;
use App\Form\ConnectionType;
use App\Form\LostPasswordType;
use App\Tools\EMailSender;
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

    /**
     * @param Request $request
     * @param EMailSender $mail
     * @return Response
     * @Route("/lostpassword", name="lost_password")
     */
    public function lostPassword(Request $request, EMailSender $mail):Response {
        $form = $this->createForm(LostPasswordType::class);
        $form->handleRequest($request);
        $error = '';
        if ($form->isSubmitted() && $form->isValid()) {
            $repoUser = $this->getDoctrine()->getRepository(User::class);
            $user = $repoUser->findOneBy(['email' => $form->get('email')->getData()]);
            if ($user === null) {
                $error = 'Unknown user!';
            } else {
                $token = new Token();
                $token->setUser($user);
                // 1 hour limitation
                $token->setTimeLimit(3600);
                $em = $this->getDoctrine()->getManager();
                $em->persist($token);
                $em->flush();
                $mail->send($user, 'lost_password', [
                    'user' => $user,
                    'key' => $token->getTokenKey(),
                ]);
                return $this->redirectToRoute("security_login");
            }
        }
        return $this->render(
            'security/lost_password.html.twig',
            [
                'form' => $form->createView(),
                'error' => $error
            ]
        );
    }
}
