<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    private $entityManager;
    private $reservationRepository;
    private $translator;

    public function __construct(
        EntityManagerInterface $entityManager,
        ReservationRepository $reservationRepository,
        TranslatorInterface $translator
    ) {
        $this->entityManager = $entityManager;
        $this->reservationRepository = $reservationRepository;
        $this->translator = $translator;
    }

    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(): Response
    {
        // Supprimer les réservations expirées avant d'afficher la liste
        $deletedCount = $this->reservationRepository->deleteExpiredReservations();
        if ($deletedCount > 0) {
            $this->addFlash('info', $this->translator->trans('reservations.expired_deleted', ['count' => $deletedCount]));
        }

        $reservations = $this->reservationRepository->findAll();
        $reservationCount = count($reservations);

        // Calculate count of reservations per subscription type
        $typeCounts = [];
        foreach ($reservations as $reservation) {
            $type = $reservation->getTypeAbonnement();
            $typeName = $type ? $type->getNom() : 'N/A';
            $typeCounts[$typeName] = ($typeCounts[$typeName] ?? 0) + 1;
        }

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
            'reservation_count' => $reservationCount,
            'type_counts' => $typeCounts, // Pass the counts to the template
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation): Response
    {
        // Vérifier si la réservation à modifier est expirée
        $currentDate = new \DateTime();
        if ($reservation->getDateFin() && $reservation->getDateFin() <= $currentDate) {
            $this->entityManager->remove($reservation);
            $this->entityManager->flush();
            $this->addFlash('info', $this->translator->trans('reservation.expired_deleted'));
            return $this->redirectToRoute('app_reservation_index');
        }

        // Supprimer les autres réservations expirées
        $deletedCount = $this->reservationRepository->deleteExpiredReservations();
        if ($deletedCount > 0) {
            $this->addFlash('info', $this->translator->trans('reservations.expired_deleted', ['count' => $deletedCount]));
        }

        $form = $this->createForm(ReservationType::class, $reservation, [
            'is_edit' => true // Ajout de cette option
        ]);
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('reservation.updated'));
            return $this->redirectToRoute('app_reservation_index');
        }
    
        return $this->render('reservation/edit.html.twig', [
            'form' => $form->createView(),
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation): Response
    {
        // Supprimer les réservations expirées avant de supprimer manuellement
        $deletedCount = $this->reservationRepository->deleteExpiredReservations();
        if ($deletedCount > 0) {
            $this->addFlash('info', $this->translator->trans('reservations.expired_deleted', ['count' => $deletedCount]));
        }

        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($reservation);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('deleted'));
        }

        return $this->redirectToRoute('app_reservation_index');
    }

    // Méthode temporaire pour créer des réservations de test
    #[Route('/create-test', name: 'app_reservation_create_test', methods: ['GET'])]
    public function createTestReservations(): Response
    {
        // Réservation expirée (dateFin dans le passé)
        $reservation1 = new Reservation();
        $reservation1->setDateFin(new \DateTime('2024-05-11 19:03:00')); // Date passée (11/05/2024)
        $reservation1->setStatut('en cours');
        $this->entityManager->persist($reservation1);

        // Réservation non expirée (dateFin dans le futur)
        $reservation2 = new Reservation();
        $reservation2->setDateFin(new \DateTime('2025-04-30 19:03:00')); // Date future (30/04/2025)
        $reservation2->setStatut('en attente');
        $this->entityManager->persist($reservation2);

        // Réservation sans dateFin (pour tester le cas nullable)
        $reservation3 = new Reservation();
        $reservation3->setStatut('en cours');
        $this->entityManager->persist($reservation3);

        $this->entityManager->flush();

        $this->addFlash('success', 'Réservations de test créées avec succès.');

        return $this->redirectToRoute('app_reservation_index');
    }
}