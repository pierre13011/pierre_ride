<?php

namespace App\Controller;

use App\Repository\TrajetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trajet;
use App\Form\TrajetType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ChauffeurController extends AbstractController
{
    #[Route('/devenir-chauffeur', name: 'app_devenir_chauffeur')]
    public function devenirChauffeur(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if(!$user) {
            return $this->redirectToRoute('app_login');
        }

        $user->setRole('ROLE_CHAUFFEUR');
        $em->flush();

        $this->addFlash('success', 'Félicitations 🎉 vous êtes maintenant chauffeur !');

        return $this->redirectToRoute('app_profile');
    }

    #[Route('/chauffeur/espace', name: 'app_chauffeur_espace')]
    #[IsGranted("ROLE_CHAUFFEUR")]
    public function espace(TrajetRepository $repo): Response
    {
        $trajets = $repo->findBy(['chauffeur' => $this->getUser()]);

        return $this->render('chauffeur/espace.html.twig', [
            'trajets' => $trajets,
        ]);
    }

    #[Route('/chauffeur/trajet/{id}/reservations', name: 'chauffeur_trajet_reservations')]
    #[IsGranted("ROLE_CHAUFFEUR")]
    public function trajetReservations(int $id, TrajetRepository $trajetRepo): Response 
    {
        $trajet = $trajetRepo->find($id);

        if (!$trajet) {
            throw $this->createNotFoundException("Trajet introuvable");
        }

        if ($trajet->getChauffeur() !== $this->getUser()) {
            $this->addFlash('error', 'Accès refusé ❌');
            return $this->redirectToRoute('app_chauffeur_espace');
        }

        return $this->render('chauffeur/reservations.html.twig', [
            'trajet' => $trajet
        ]);
    }

    #[Route('/chauffeur/trajet/new', name: 'app_chauffeur_trajet_new')]
    #[IsGranted("ROLE_CHAUFFEUR")]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $trajet = new Trajet();
        $form = $this->createForm(TrajetType::class, $trajet);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $trajet->setChauffeur($this->getUser());

            $em->persist($trajet);
            $em->flush();

            $this->addFlash('success', 'Trajet créé avec succès 🚗');

            return $this->redirectToRoute('app_chauffeur_espace');
        }

        return $this->render('chauffeur/form_trajet.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
