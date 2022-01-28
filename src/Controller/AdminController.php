<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\EditUserType;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('admin/users.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    /**
     * @route("/utilisateurs", name="utilisateurs")
     */
    public function usersListe(UsersRepository $users)
    {
        return $this->render("admin/users.html.twig",[
            'users' => $users->findAll()
        ]);
    }


    /**
     * @route("/utilisateur/modifier/{id}", name="modifier_utilisateur")
     */
    public function editUser(Users $user, Request $request)
    {

        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();



            $this->addFlash('message', 'utilisateur modifie avec succes');
            return $this->redirectToRoute('admin_utilisateurs');
        }


        return $this->render('admin/edituser.html.twig', [
            'userForm' => $form->createView()
        ]);

    }












}
