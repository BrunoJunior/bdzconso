<?php

namespace App\Controller;

use App\Business\UserBO;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{

    /**
     * @var UserBO
     */
    private $userBO;

    /**
     * UserController constructor.
     * @param UserBO $userBO
     */
    public function __construct(UserBO $userBO)
    {
        $this->userBO = $userBO;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     * @Route("/account/edit", name="my_account_edit")
     */
    public function show(Request $request) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        return $this->edit($request, $this->getUser());
    }

    /**
     * @param Request $request
     * @param User $user
     * @return Response
     * @throws \Exception
     * @Route("/user/{id}", name="admin_user",
     *     defaults={"id"= 0},
     *     requirements={
     *         "id": "\d+"
     *     })
     */
    public function edit(Request $request, User $user = null)
    {
        if ($user === null || $this->getUser() === null || $this->getUser()->getId() !== $user->getId()) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        if ($user === null) {
            $user = new User();
        }
        $form = $this->createForm(UserType::class, $user, ['admin' => $this->isGranted('ROLE_ADMIN'), 'edit' => $user->getId() > 0]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userBO->encodeAndSetPassword($user, $form->get('password')->getData());
            // Saving user in DB
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            // Activate or deactivate API for the user
            $activateApi = (bool) $form->get('apiActivated')->getData();
            $this->userBO->manageApiKey($user, $activateApi);
            $em->flush();

            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('admin_users');
            }
            return $this->redirectToRoute('my_account');
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'form' => $form->createView(),
                'new' => $user->getId() == 0,
                'user' => $user
            ]
        );
    }
    /**
     * @param User $user
     * @return Response
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
