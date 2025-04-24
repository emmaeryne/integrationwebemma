<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Service;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Psr\Log\LoggerInterface;

#[Route('/client')]
class ReservationClientController extends AbstractController
{
    private $entityManager;
    private $reservationRepository;
    private $serviceRepository;
    private $translator;
    private $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        ReservationRepository $reservationRepository,
        ServiceRepository $serviceRepository,
        TranslatorInterface $translator,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->reservationRepository = $reservationRepository;
        $this->serviceRepository = $serviceRepository;
        $this->translator = $translator;
        $this->logger = $logger;
    }

    #[Route('/', name: 'app_client_reservation_index', methods: ['GET'])]
    public function index(): Response
    {
        // Mettre à jour le statut des réservations expirées avant d'afficher la liste
        $updatedCount = $this->reservationRepository->updateExpiredReservationsStatus();
        if ($updatedCount > 0) {
            $this->addFlash('info', $this->translator->trans('reservations.status_updated', ['count' => $updatedCount]));
        }

        $reservations = $this->reservationRepository->findAll();
        $services = $this->serviceRepository->findAll();
    
        // Ajouter des valeurs par défaut pour éviter les null
        foreach ($services as $service) {
            if (null === $service->getSalle()) {
                $service->setSalle('N/A');
            }
            if (null === $service->getDescription()) {
                $service->setDescription('Aucune description disponible');
            }
            // Log des données pour débogage
            $this->logger->info('Service: ' . $service->getNom(), [
                'salle' => $service->getSalle(),
                'description' => $service->getDescription(),
            ]);
        }
    
        return $this->render('client/client_index.html.twig', [
            'reservations' => $reservations,
            'services' => $services,
        ]);
    }

    #[Route('/new', name: 'app_client_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $reservation = new Reservation();
    
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$reservation->getDateFin() && $reservation->getDateDebut()) {
                $duree = $reservation->getTypeAbonnement()->getDureeEnMois();
                $dateDebut = $reservation->getDateDebut();
    
                if (!($dateDebut instanceof \DateTimeInterface)) {
                    throw new \LogicException('DateDebut must be a DateTime or DateTimeImmutable object.');
                }
    
                $dateFin = clone $dateDebut;
                // $dateFin->modify("+$duree months");
                $reservation->setDateFin($dateFin);
            }
    
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();
    
            $this->addFlash('success', $this->translator->trans('created'));
            return $this->redirectToRoute('app_client_reservation_index');
        }
    
        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation): Response
    {
        // Vérifier si la réservation à modifier est expirée et mettre à jour son statut
        $currentDate = new \DateTime();
        if ($reservation->getDateFin() && $reservation->getDateFin() <= $currentDate && $reservation->getStatut() !== 'terminé') {
            $reservation->setStatut('terminé');
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();
            $this->addFlash('info', $this->translator->trans('reservation.status_updated_single'));
        }

        // Mettre à jour le statut des autres réservations expirées
        $updatedCount = $this->reservationRepository->updateExpiredReservationsStatus();
        if ($updatedCount > 0) {
            $this->addFlash('info', $this->translator->trans('reservations.status_updated', ['count' => $updatedCount]));
        }

        $form = $this->createForm(ReservationType::class, $reservation, [
            'is_edit' => true // Ajout de cette option
        ]);
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('updated'));
            return $this->redirectToRoute('app_client_reservation_index');
        }
    
        return $this->render('client/edit.html.twig', [
            'form' => $form->createView(),
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_client_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation): Response
    {
        // Mettre à jour le statut des réservations expirées avant de supprimer manuellement
        $updatedCount = $this->reservationRepository->updateExpiredReservationsStatus();
        if ($updatedCount > 0) {
            $this->addFlash('info', $this->translator->trans('reservations.status_updated', ['count' => $updatedCount]));
        }

        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($reservation);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('deleted'));
        }

        return $this->redirectToRoute('app_client_reservation_index');
    }

    #[Route('/{id}/badge', name: 'app_client_reservation_badge', methods: ['GET'])]
    public function generateBadge(Reservation $reservation): Response
    {
        $this->logger->info('Starting badge generation for reservation ID: ' . $reservation->getId());

        // Vérifier si la réservation est expirée et mettre à jour son statut
        $currentDate = new \DateTime();
        if ($reservation->getDateFin() && $reservation->getDateFin() <= $currentDate && $reservation->getStatut() !== 'terminé') {
            $reservation->setStatut('terminé');
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();
            $this->addFlash('info', $this->translator->trans('reservation.status_updated_single'));
        }

        // Mettre à jour le statut des autres réservations expirées
        $updatedCount = $this->reservationRepository->updateExpiredReservationsStatus();
        if ($updatedCount > 0) {
            $this->addFlash('info', $this->translator->trans('reservations.status_updated', ['count' => $updatedCount]));
        }

        // Vérifier si le statut est "en cours"
        if ($reservation->getStatut() !== 'en cours') {
            $this->logger->info('Badge generation aborted: Status is not "en cours" for reservation ID: ' . $reservation->getId());
            $this->addFlash('error', $this->translator->trans('badge.not_allowed'));
            return $this->redirectToRoute('app_client_reservation_index');
        }

        // Prepare badge data with null checks
        $typeAbonnement = $reservation->getTypeAbonnement() ? $reservation->getTypeAbonnement()->getNom() : 'N/A';
        $startDate = $reservation->getDateDebut() ? $reservation->getDateDebut()->format('d/m/Y') : '-';
        $endDate = $reservation->getDateFin() ? $reservation->getDateFin()->format('d/m/Y') : '-';
        $status = $this->translator->trans($reservation->getStatut() ?? 'unknown');
    
        $this->logger->info('Badge data prepared: ' . json_encode(compact('typeAbonnement', 'startDate', 'endDate', 'status')));
    
        // Generate barcode (Code 128)
        $barcodeData = (string) $reservation->getId(); // Ensure barcodeData is a string
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($barcodeData, $generator::TYPE_CODE_128, 3, 80);
    
        // Create badge image
        $badgeWidth = 450;
        $badgeHeight = 250;
        $image = imagecreatetruecolor($badgeWidth, $badgeHeight);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $gray = imagecolorallocate($image, 200, 200, 200);
        $lightBlue = imagecolorallocate($image, 220, 235, 255);
        imagefill($image, 0, 0, $white);
    
        // Add header background
        imagefilledrectangle($image, 0, 0, $badgeWidth, 50, $lightBlue);
    
        // Add logo (centered at top)
        $logoPath = $this->getParameter('kernel.project_dir') . '/public/images/logo.png';
        if (file_exists($logoPath)) {
            $logo = imagecreatefrompng($logoPath);
            $logoWidth = imagesx($logo);
            $logoHeight = imagesy($logo);
            $logoX = ($badgeWidth - 60) / 2;
            imagecopyresized($image, $logo, $logoX, 5, 0, 0, 60, 40, $logoWidth, $logoHeight);
            imagedestroy($logo);
        } else {
            $this->logger->warning('Logo file not found at: ' . $logoPath);
        }
    
        // Add text
        $font = 'C:\Windows\Fonts\arial.ttf';
        if (!file_exists($font)) {
            $this->logger->error('Font file not found at: ' . $font);
            $font = 'C:\Windows\Fonts\arialbd.ttf';
        }
        imagettftext($image, 16, 0, 10, 80, $black, $font, "Client Badge");
        imagettftext($image, 12, 0, 10, 110, $black, $font, "Subscription: $typeAbonnement");
        imagettftext($image, 12, 0, 10, 140, $black, $font, "Start Date: $startDate");
        imagettftext($image, 12, 0, 10, 170, $black, $font, "End Date: $endDate");
        imagettftext($image, 12, 0, 10, 200, $black, $font, "Status: $status");
    
        // Add barcode (right-aligned)
        $barcodeImage = imagecreatefromstring($barcode);
        $barcodeWidth = imagesx($barcodeImage);
        $barcodeHeight = imagesy($barcodeImage);
        $barcodeX = $badgeWidth - $barcodeWidth - 10;
        imagecopy($image, $barcodeImage, $barcodeX, 90, 0, 0, $barcodeWidth, $barcodeHeight);
    
        // Add border
        imagerectangle($image, 0, 0, $badgeWidth - 1, $badgeHeight - 1, $gray);
    
        // Output the image as a downloadable PNG
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);
        imagedestroy($barcodeImage);
    
        $this->logger->info('Badge generated and prepared for download for reservation ID: ' . $reservation->getId());
    
        return new Response($imageData, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="badge-' . $reservation->getId() . '.png"',
        ]);
    }
}