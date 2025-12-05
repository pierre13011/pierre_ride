<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    #[IsGranted("ROLE_USER")]
        public function index(): Response
        {
            $user = $this->getUser();
            $trajets = $user->getTrajets(); // Récupère les trajets du chauffeur

            return $this->render('profile/index.html.twig', [
                'user' => $user,
                'trajets' => $trajets
            ]);
        }
}


