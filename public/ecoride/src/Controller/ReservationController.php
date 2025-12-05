<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\TrajetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservation/new/{trajet_id}', name: 'app_reservation_new')]
    public function new(
        int $trajet_id,
        Request $request,
        EntityManagerInterface $em,
        TrajetRepository $trajetRepo
    ): Response {

        // User connecté obligatoire
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté.');
            return $this->redirectToRoute('app_login');
        }

        // Récupération du trajet
        $trajet = $trajetRepo->find($trajet_id);
        if (!$trajet) {
            throw $this->createNotFoundException('Trajet introuvable.');
        }

        // Empêcher chauffeur de réserver son propre trajet
        if ($trajet->getChauffeur() === $user) {
            $this->addFlash('error', 'Impossible de réserver votre propre trajet');
            return $this->redirectToRoute('app_trajet_show', ['id' => $trajet_id]);
        }

        // Places restantes
        $placesRestantes = $trajet->getPlacesRestantes();

        if ($placesRestantes <= 0) {
            $this->addFlash('error', "Ce trajet est complet !");
            return $this->redirectToRoute('app_trajet_show', ['id' => $trajet_id]);
        }

        // Préparation réservation
        $reservation = new Reservation();
        $reservation->setUser($user);
        $reservation->setTrajet($trajet);
        $reservation->setDateReservation(new \DateTime());

        // Formulaire
        $form = $this->createForm(ReservationType::class, $reservation, [
            'max_places' => $placesRestantes
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $placesDemandées = $reservation->getPlaces();

            // Sécurité double-check
            if ($placesDemandées > $placesRestantes) {
                $this->addFlash('error', "Pas assez de places disponibles. Il reste $placesRestantes place(s).");
                return $this->redirectToRoute('app_reservation_new', [
                    'trajet_id' => $trajet_id
                ]);
            }

            $em->persist($reservation);
            $em->flush();

            $this->addFlash('success', "Réservation confirmée 🎉");

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form,
            'trajet' => $trajet,
            'placesRestantes' => $placesRestantes
        ]);
    }
}
