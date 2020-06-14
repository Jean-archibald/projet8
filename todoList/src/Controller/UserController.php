<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user_list")
     * @IsGranted("ROLE_ADMIN")
     */
    public function listAction()
    {
        return $this->render('user/list.html.twig', ['users' => $this->getDoctrine()->getRepository('App:User')->findAll()]);
    }

    /**
     * @Route("/users/create", name="user_create")
     * @Route("/users/{id}/edit", name="user_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createAction(User $user = null, Request $request,EntityManagerInterface $manager, UserRepository $userRepository,UserPasswordEncoderInterface $encoder)
    {
        if(!$user) {
            $user = new User();
        }

        $formUser = $this->createForm(UserType::class, $user);
        $formUser->handleRequest($request);

        if ($formUser->isSubmitted() && $formUser->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();

            if(!$user->getId()) {
                $this->addFlash('success', "L'utilisateur a bien été ajouté.");
            }
            else
            {
                $this->addFlash('success', "L'utilisateur a bien été modifié.");
            }

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', [
            'formUser' => $formUser->createView(),
            'editMode' => $user->getId() !== null
        ]);
    }

}
