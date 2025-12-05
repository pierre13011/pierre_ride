<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EmailVerifiedChecker implements EventSubscriberInterface
{
    public function __construct(private RouterInterface $router) {}

    public function onLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof UserInterface) {
            // Vérifie le champ isVerified
            if (method_exists($user, 'isVerified') && !$user->isVerified()) {
                throw new CustomUserMessageAuthenticationException(
                    "Vous devez d'abord activer votre compte via l'email envoyé."
                );
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            InteractiveLoginEvent::class => 'onLogin'
        ];
    }
}
