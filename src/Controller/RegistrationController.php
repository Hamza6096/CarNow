<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UsersAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager, SendMailService $mail, JWTService $jwt): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            //Générer le JWT de l'utilisateur
            //Créer le Header
            $header = [
                "alg" => "HS256",
                "typ" => "JWT"
            ];

            //Créer le payload
            $payload = [
                'user_id' => $user->getId()
            ];

            //Générer le token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));


            //Envoyer un mail
            $mail->send(
                'no-replay@monsite.net',
                $user->getEmail(),
                'Activation de votre compte sur le site CarNow',
                'register',
                [
                    'user' => $user,
                    'token' => $token
                ]
            );


            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, UserRepository $userRepository, EntityManagerInterface $manager): Response
    {
        //Vérifier si le token est valide, n'a pas expiré, n'a pas été modifier
        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))) {
            //récuperer le payload
            $payload = $jwt->getPayload($token);

            //Récupérer le user du token
            $user = $userRepository->find($payload['user_id']);

            //Vérifier que l'utilisateur éxiste et n'a pas encore activé son compte
            if ($user && !$user->getIsVerified()) {
                $user->setIsverified(true);
                $manager->flush();
                $this->addFlash('success', 'Utilisateur activé');
                return $this->redirectToRoute('home');
            }
        }
        //si un probleme dans le token
        $this->addFlash('danger', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('login');

    }

    #[Route('/resendverif', name: 'resend_verif')]
    public function resendVerif(JWTService $jwt, SendMailService $mail, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('login');
        }
        if ($user->getIsVerified()) {
            $this->addFlash('warning', 'cette utilisateur est déjà activé');
            return $this->redirectToRoute('home');
        }

        //Générer le JWT de l'utilisateur
        //Créer le Header
        $header = [
            "alg" => "HS256",
            "typ" => "JWT"
        ];

        //Créer le payload
        $payload = [
            'user_id' => $user->getId()
        ];

        //Générer le token
        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));


        //Envoyer un mail
        $mail->send(
            'no-replay@monsite.net',
            $user->getEmail(),
            'Activation de votre compte sur le site CarNow',
            'register',
            [
                'user' => $user,
                'token' => $token
            ]
        );
        $this->addFlash('success', 'Email de vérification envoyé');
        return $this->redirectToRoute('home');
    }
}

