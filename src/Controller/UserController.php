<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserUpdateType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller
{
    /**
     * @Route("/user/{id}", name="admin_user")
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder, User $user)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(UserUpdateType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('new_password');
            if (!$password->isEmpty()) {
                $password = $passwordEncoder->encodePassword($user, $password->getData());
                $user->setPassword($password);
            }
            // Par defaut l'utilisateur aura toujours le rôle ROLE_USER
            $roles = new ArrayCollection($user->getRoles());
            if ($roles->isEmpty()) {
                $user->setRoles(['ROLE_USER']);
            }

            // On enregistre l'utilisateur dans la base
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render(
            'user/edit.html.twig',
            ['form' => $form->createView(), 'new' => false]
        );
    }
    /**
     * @Route("/user/{id}/delete", name="delete_user")
     */
    public function delete(User $user)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
