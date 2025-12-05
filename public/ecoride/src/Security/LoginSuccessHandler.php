<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private RouterInterface $router
    ) {}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        /** @var User $user */
        $user = $token->getUser();

        // 1️⃣ Compte non vérifié → page bloquée
        if (!$user->isVerified()) {
            return new RedirectResponse($this->router->generate('app_email_not_verified'));
        }

        // 2️⃣ Redirection selon rôle
        if ($user->getRole() === 'ROLE_CHAUFFEUR') {
            return new RedirectResponse($this->router->generate('app_chauffeur_espace'));
        }

        // 3️⃣ Sinon → page profil
        return new RedirectResponse($this->router->generate('app_profile'));
    }
}