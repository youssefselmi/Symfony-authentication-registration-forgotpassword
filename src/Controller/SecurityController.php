<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ResetPassType;
use App\Repository\UsersRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use Symfony\Component\HttpFoundation\Request;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

        //return $this->redirectToRoute('template');


    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }



    /**
     * @route("/oubli-pass", name="app_forgotten_password")
     */
    public function forgottenPass(Request $request, UsersRepository $userRepo, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator){

    $form = $this->createForm(ResetPassType::class);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
      $donnes = $form->getData();

      $user = $userRepo->findOneByEmail($donnes['email']);
      if(!$user){
          $this->addFlash('danger', 'cette adresse n exiqte pas');
         return $this->redirectToRoute("app_login");
      }


      $token = $tokenGenerator->generateToken();

        try {
            $user->setResetToken($token);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }catch (\Exception $e){
            $this->addFlash('warning','une erreur est devenue : '.$e->getMessage());
            return $this->redirectToRoute("app_login");

        }

        $url = $this->generateUrl('app_reset_password', ['token'=>$token],UrlGeneratorInterface::ABSOLUTE_URL);


        $message = (new \Swift_Message('Mot de passe oubliée'))
            ->setFrom('youssefselmi99@gmail.com')
            ->setTo($user->getEmail())
            ->setBody("<p>Bonjour ,</p><p>Une demande de reinitialisation de mot de passe a ete effectues . Veuillercliquer sur lelien suivant: ".$url.'</p>','text/html');
        $mailer->send($message);

        $this->addFlash('message', 'un email de reinitiaisation de mot de passe  vous a ete envoye');

        return $this->redirectToRoute('app_login');

    }

    return $this->render('security/forgotten_password.html.twig', ['emailForm' => $form->createView()]);


    }

    /**
     * @route ("/reset-pass/{token}", name="app_reset_password")
     */

     public function resetPassword($token,Request $request, UserPasswordEncoderInterface $passwordEncoder){

         $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['reset_token'=>$token]);

         if(!$user){
             $this->addFlash('danger','token inconnu');
             return $this->redirectToRoute('app_login');
         }

         if($request->isMethod('post')){
             $user->setResetToken(null);

             $user->setPassword($passwordEncoder->encodePassword($user, $request->get('password')));
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($user);
             $entityManager->flush();

             $this->addFlash('message', 'Mot de passe modifie avec succes');

             return $this->redirectToRoute('app_login');

         }else
         {
             return $this->render('security/reset_password.html.twig', ['token'=> $token ]);
         }


     }




}
