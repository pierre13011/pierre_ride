<?php

namespace App\Controller;

use App\Entity\Trajet;
use App\Form\TrajetType;
use App\Repository\TrajetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/trajet')]
class TrajetController extends AbstractController
{
    #[Route('/', name: 'app_trajet_index', methods: ['GET'])]
    public function index(TrajetRepository $trajetRepository): Response
    {
        return $this->render('trajet/index.html.twig', [
            'trajets' => $trajetRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_CHAUFFEUR')]
    #[Route('/new', name: 'app_trajet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $trajet = new Trajet();
        $trajet->setChauffeur($this->getUser());

        $form = $this->createForm(TrajetType::class, $trajet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($trajet);
            $em->flush();

            $this->addFlash('success', 'Trajet créé avec succès 🚗💨');

            return $this->redirectToRoute('app_trajet_index');
        }

        return $this->render('trajet/new.html.twig', [
            'trajet' => $trajet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trajet_show', methods: ['GET'])]
    public function show(Trajet $trajet): Response
    {
        return $this->render('trajet/show.html.twig', [
            'trajet' => $trajet,
        ]);
    }

    #[IsGranted('ROLE_CHAUFFEUR')]
    #[Route('/{id}/edit', name: 'app_trajet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trajet $trajet, EntityManagerInterface $em): Response
    {
        // Empêcher un chauffeur de modifier un trajet qui n'est pas à lui
        if ($trajet->getChauffeur() !== $this->getUser()) {
            $this->addFlash('error', 'Accès interdit ❌');
            return $this->redirectToRoute('app_trajet_index');
        }

        $form = $this->createForm(TrajetType::class, $trajet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Trajet mis à jour ✔');
            return $this->redirectToRoute('app_trajet_index');
        }

        return $this->render('trajet/edit.html.twig', [
            'trajet' => $trajet,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_CHAUFFEUR')]
    #[Route('/{id}', name: 'app_trajet_delete', methods: ['POST'])]
    public function delete(Request $request, Trajet $trajet, EntityManagerInterface $em): Response
    {
        if ($trajet->getChauffeur() !== $this->getUser()) {
            $this->addFlash('error', 'Accès interdit ❌');
            return $this->redirectToRoute('app_trajet_index');
        }

        if ($this->isCsrfTokenValid('delete'.$trajet->getId(), $request->request->get('_token'))) {
            $em->remove($trajet);
            $em->flush();
            $this->addFlash('success', 'Trajet supprimé 🗑');
        }

        return $this->redirectToRoute('app_trajet_index');
    }
}