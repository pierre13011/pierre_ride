<?php

namespace App\Controller;

use App\Repository\TrajetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trajet;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\ReservationRepository;

class CovoiturageController extends AbstractController
{
    #[Route('/covoiturage', name: 'app_covoiturage')]
    public function index(Request $request, TrajetRepository $trajetRepo): Response
    {
        $trajets = [];

        if ($request->query->count() > 0) {

            $depart  = $request->query->get('depart');
            $arrivee = $request->query->get('arrivee');
            $date    = $request->query->get('date');
            $heure   = $request->query->get('currentTime');

            $trajets = $trajetRepo->search(
                $depart,
                $arrivee,
                $date,
                $heure
            );
        }

        return $this->render('covoiturage/index.html.twig', [
            'trajets' => $trajets,
        ]);
    }

    #[Route('/trajet/{id}', name: 'app_trajet_details')]
    public function details(?int $id, TrajetRepository $repo): Response
    {
        $trajet = $repo->find($id);

        if (!$trajet) {
            throw new NotFoundHttpException("Trajet introuvable");
        }

        return $this->render('covoiturage/details.html.twig', [
            'trajet' => $trajet
        ]);
    }


    #[Route('/trajet/{id}/reserver', name: 'app_reserver_trajet')]
    #[IsGranted("ROLE_USER")]
    public function reserver(int $id,TrajetRepository $repo,ReservationRepository $reservationRepo,EntityManagerInterface $em,Request $request): Response 
    {

        $trajet = $repo->find($id);

        if (!$trajet) {
            throw $this->createNotFoundException("Trajet introuvable");
        }

        $user = $this->getUser();

        // 1️⃣ Vérifier si l'utilisateur a déjà réservé ce trajet
        if ($reservationRepo->hasReservation($user, $trajet)) {
            $this->addFlash('warning', 'Tu as déjà réservé ce trajet 😉');
            return $this->redirectToRoute('app_trajet_details', ['id' => $id]);
        }

        // 2️⃣ Récupérer places
        $places = $request->get('places', 1);

        // 3️⃣ Vérifier places disponibles
        if ($places > $trajet->getPlaces()) {
            $this->addFlash('error', 'Pas assez de places disponibles 😕');
            return $this->redirectToRoute('app_trajet_details', ['id' => $id]);
        }

        // 4️⃣ Créer reservation
        $reservation = new Reservation();
        $reservation->setPlaces($places);
        $reservation->setDateReservation(new \DateTime());
        $reservation->setUser($user);
        $reservation->setTrajet($trajet);

        // 5️⃣ MAJ places trajet
        $trajet->setPlaces($trajet->getPlaces() - $places);

        // 6️⃣ Persist
        $em->persist($reservation);
        $em->flush();

        $this->addFlash('success', 'Réservation confirmée 🎉');

        return $this->redirectToRoute('app_trajet_details', ['id' => $id]);
    }
}