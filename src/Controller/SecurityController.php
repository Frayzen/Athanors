<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use SwiftMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @param UserRepository $userRepository
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, UserRepository $userRepository): Response
    {
        if ($this->getUser())
             return $this->redirectToRoute('home');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $msg = $error == null ?  null : "Les informations fournies sont incorrectes";
        if ($error instanceof DisabledException)
            $msg = "<a href='". $this->generateUrl("send_mail", ['id'=>$userRepository->findOneBy(['email'=>$lastUsername])->getId()])."'>Votre compte n'est pas activé, cliquez ici pour renvoyer un mail</a>";
        // last username entered by the user

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $msg]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    /**
     * @Route("/account/enable/{code}", name="enable_account")
     * @param Request $request
     * @param string $code
     * @param UserRepository $userRepository
     * @return Response
     */
    public function enable_account(Request $request, string $code, UserRepository $userRepository){

        $user = $userRepository->findOneBy(['activation_id'=>$code]);
        if ($user == null)
            return $this->render("user/modal.twig", ["pathRedirect"=>$this->generateUrl("home"), "error"=>true, "message"=>"Un problème est survenu", "title"=>"Oups..."]);
        $user->setActivated(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->render("user/modal.twig", ["pathRedirect"=>$this->generateUrl("app_login"), "error"=>false, "message"=>"Compte enregistré avec succés", "title"=>"Activation du compte"]);
    }

    /**
     * @Route("/account/created/{id}", name="send_mail")
     * @param User $user
     * @param MailerInterface $mailer
     * @param Request $request
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function send_mail(User $user, MailerInterface $mailer, Request $request){
        $mail = (new Email())
            ->from('noreply@deriveenrive.com')
            ->to($user->getEmail())
            ->subject("[DeRiveEnRive] Activation du compte")
            ->html("
                <h1>Activation du compte </h1><p>Cliquez <b><a href='https://".$request->getHttpHost().$this->generateUrl("enable_account", ['code'=>$user->getActivationId()])."'>ici</a></b> pour activer le compte</p>
            ")
        ;
        $mailer->send($mail);

        return $this->render("user/modal.twig", ["pathRedirect"=>$this->generateUrl("home"), "error"=>false, "message"=>"Un mail vient de vous être envoyé à l'adresse \" ".$user->getEmail()." \", consulter vos mails pour activer le compte (pensez à regarder dans les spams) ", "title"=>"Mail envoyé !"]);
    }

    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param SwiftMailer $mailer
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword($passwordEncoder->encodePassword(
                             $user,
                             $user->getPassword()
                         ));
            $user->setRegular(false);

            $code = uniqid();

            $user->setActivated(false);
            $user->setActivationId($code);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute("send_mail", ['id'=>$user->getId()]);
        }
        // a simple, but incomplete (see below) way to collect all errors
        $errors = array();
        foreach ($form as $fieldName => $formField) {
            // each field has an array of errors
            foreach ($formField->getErrors() as $error)
                array_push($errors, $error);
        }
        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
            'error' => true,
            'list_error' => $errors
        ]);

    }
}
