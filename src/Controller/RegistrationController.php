<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\UsersAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UsersAuthenticator $authenticator, \Swift_Mailer $mailer): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $contact=$form->getData();
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );


            // on genere le token d'activation

            $user->setActivationToken(md5(uniqid()));



            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email




            $message = (new \Swift_Message('Activation de votre compte'))
                ->setFrom('youssefselmi99@gmail.com')
                ->setTo($user->getEmail())
                ->setBody('hello')
                ->setBody($this->renderView(
                    'emails/activation.html.twig', ['token' => $user->getActivationToken()]
                ), 'text/html')

            ->attach(\Swift_Attachment::fromPath('C:\Users\ASUS\Music\Templatecv\wevioo.png'));
            $mailer->send($message);

            $this->addFlash('message', 'le message à bien été envoyé');





            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );




        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @param $token
     * @param UsersRepository $userRepo
     * @route("/activation/{token}", name="activation")
     */

    public function activation($token, UsersRepository $userRepo){
       $user = $userRepo->findOneBy(['activation_token' => $token]);

       if(!$user){
           throw $this->createNotFoundException('Cet utilisateur n\'exite pas');
       }
       $user->setActivationToken(null);
       $entityManager  = $this->getDoctrine()->getManager();
       $entityManager->persist($user);
       $entityManager->flush();

       $this->addFlash('message', 'Vous avez bien activé votre compte');

       return $this->redirectToRoute('template');

    }


}
