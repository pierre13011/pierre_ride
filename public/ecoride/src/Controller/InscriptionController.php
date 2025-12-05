<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        MailerInterface $mailer,
        UrlGeneratorInterface $urlGenerator
    ): Response {

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $token = bin2hex(random_bytes(32));
            $user->setVerificationToken($token);

            $em->persist($user);
            $em->flush();

            $activationLink = $urlGenerator->generate(
                'app_confirm_email',
                ['token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $email = (new Email())
                ->from('ecoride@ecoride.fr')
                ->to($user->getEmail())
                ->subject('Activez votre compte EcoRide 🚗🌿')
                ->html("
                    <h1>Bienvenue {$user->getPrenom()} !</h1>
                    <p>Pour activer votre compte, cliquez ci-dessous :</p>
                    <a href='{$activationLink}'
                       style='background:#28a745;color:white;padding:10px 20px;text-decoration:none;border-radius:5px'>
                        Activer mon compte
                    </a>
                ");

            $mailer->send($email);

            $this->addFlash('success', 'Inscription réussie 🎉 Vérifiez votre email pour activer votre compte.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('inscription/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}


