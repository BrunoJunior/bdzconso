<?php

namespace App\Controller;

use App\Business\TokenBO;
use App\Entity\Token;
use App\Form\NewPasswordType;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TokenController extends Controller
{
    /**
     * @Route("/token/{key}/reset_password", name="token_razpwd")
     */
    public function validateResetPassword(string $key, TokenBO $tokenBO, LoggerInterface $logger, UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        $form = $this->createForm(NewPasswordType::class);
        $form->handleRequest($request);
        $repo = $this->getDoctrine()->getRepository(Token::class);
        $em = $this->getDoctrine()->getManager();
        $token = $repo->findOneByKey($key);
        $error = $form->getErrors();
        try {
            if (!$tokenBO->isValid($token)) {
                $error = 'The link is no more valid!';
            }
        } catch (\Exception $ex) {
            $logger->error($ex->getMessage());
            $error = $ex->getMessage();
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $token->setValidated(true);
            $em->persist($token);

            $user = $token->getUser();
            $password = $passwordEncoder->encodePassword($user, $form->get('password')->getData());
            $user->setPassword($password);
            $em->persist($user);

            $em->flush();
            return $this->render('token/password_reset.html.twig');
        }
        return $this->render('token/reset_password.html.twig', [
            'error' => $error,
            'form' => $form->createView(),
        ]);
    }
}
