<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfirmEmailController extends AbstractController
{
    #[Route('/confirm-email/{token}', name: 'app_confirm_email')]
    public function confirmEmail(
        string $token,
        UserRepository $userRepo,
        EntityManagerInterface $em
    ): Response {

        $user = $userRepo->findOneBy(['verificationToken' => $token]);

        if (!$user) {
            $this->addFlash('error', 'Lien de confirmation invalide ❌');
            return $this->redirectToRoute('app_home');
        }

        // Active le compte
        $user->setIsVerified(true);
        $user->setVerificationToken(null);

        $em->flush();

        $this->addFlash('success', 'Votre compte a été activé avec succès 🎉 Vous pouvez maintenant vous connecter.');

        return $this->redirectToRoute('app_home');
    }
}
