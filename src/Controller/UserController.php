<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller
{
    /**
     * @Route("/user/{id}", name="admin_user",
     *     defaults={"id"= 0},
     *     requirements={
     *         "id": "\d+"
     *     })
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder, User $user = null)
    {
        if ($user === null || $this->getUser()->getId() !== $user->getId()) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        if ($user === null) {
            $user = new User();
        }
        $form = $this->createForm(UserType::class, $user, ['admin' => true, 'edit' => $user->getId() > 0]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password');
            if (!$password->isEmpty()) {
                $user->setPassword($passwordEncoder->encodePassword($user, $password->getData()));
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

            return $this->redirectToRoute('admin_users');
        }

        return $this->render(
            'user/edit.html.twig',
            ['form' => $form->createView(), 'new' => $user->getId() == 0 ]
        );
    }
    /**
     * @Route("/user/{id}/delete", name="delete_user")
     */
    public function delete(User $user)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('admin_users');
    }
}
