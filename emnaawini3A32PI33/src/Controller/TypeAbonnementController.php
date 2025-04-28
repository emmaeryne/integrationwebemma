<?php

namespace App\Controller;

use App\Entity\TypeAbonnement;
use App\Form\TypeAbonnementType;
use App\Repository\TypeAbonnementRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

#[Route('/type-abonnement')]
class TypeAbonnementController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private TypeAbonnementRepository $typeAbonnementRepository;
    private ReservationRepository $reservationRepository;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        TypeAbonnementRepository $typeAbonnementRepository,
        ReservationRepository $reservationRepository,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->typeAbonnementRepository = $typeAbonnementRepository;
        $this->reservationRepository = $reservationRepository;
        $this->logger = $logger;
    }

    #[Route('/', name: 'app_type_abonnement_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $sort = $request->query->getAlnum('sort', '');
        $search = $request->query->get('search', '');
        $expandedReservations = $request->query->all('expand') ?: [];
        $page = $request->query->getInt('page', 1);
        $limit = 5; // Number of items per page

        try {
            // Count total items for pagination
            $totalItems = $this->typeAbonnementRepository->countBySearch($search);
            $totalPages = max(1, ceil($totalItems / $limit));

            // Ensure valid page number
            $page = max(1, min($page, $totalPages));
            $offset = ($page - 1) * $limit;

            // Fetch items for the current page
            $typeAbonnements = $this->typeAbonnementRepository->findAllSorted($sort, $search, $limit, $offset);

            // Handle AJAX requests
            if ($request->isXmlHttpRequest()) {
                $data = [
                    'typeAbonnements' => array_map(function (TypeAbonnement $typeAbonnement) {
                        return [
                            'id' => $typeAbonnement->getId(),
                            'nom' => $typeAbonnement->getNom(),
                            'prix' => $typeAbonnement->getPrix(),
                            'dureeEnMois' => $typeAbonnement->getDureeEnMois(),
                            'isPremium' => $typeAbonnement->getIsPremium() ? 'Oui' : 'Non',
                            'description' => $typeAbonnement->getDescription(),
                            'reduction' => $typeAbonnement->getReduction(),
                            'prixReduit' => $typeAbonnement->getPrixReduit(),
                            'updatedAt' => $typeAbonnement->getUpdatedAt() ? $typeAbonnement->getUpdatedAt()->format('d/m/Y H:i:s') : null,
                            'reservationCount' => $typeAbonnement->getReservations()->count(),
                        ];
                    }, $typeAbonnements),
                    'currentPage' => $page,
                    'totalPages' => $totalPages,
                    'totalItems' => $totalItems,
                ];
                return new JsonResponse($data);
            }
        } catch (\Exception $e) {
            $this->logger->error('Error fetching subscriptions', [
                'exception' => $e->getMessage(),
                'search' => $search,
                'sort' => $sort,
                'page' => $page,
            ]);
            $this->addFlash('error', "Erreur lors de la récupération des types d'abonnements.");

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['error' => "Erreur lors de la récupération des types d'abonnements."], 500);
            }

            $typeAbonnements = [];
            $totalPages = 1;
            $page = 1;
        }

        return $this->render('type_abonnement/index.html.twig', [
            'type_abonnements' => $typeAbonnements,
            'sort' => $sort,
            'search' => $search,
            'expanded_reservations' => $expandedReservations,
            'current_page' => $page,
            'total_pages' => $totalPages,
        ]);
    }

    #[Route('/new', name: 'app_type_abonnement_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $typeAbonnement = new TypeAbonnement();
        $form = $this->createForm(TypeAbonnementType::class, $typeAbonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->prepareTypeAbonnement($typeAbonnement);
                $this->entityManager->persist($typeAbonnement);
                $this->entityManager->flush();

                $this->addFlash('success', "Type d'abonnement créé avec succès.");
                return $this->redirectToRoute('app_type_abonnement_index');
            } catch (\Exception $e) {
                $this->logger->error('Error creating subscription', [
                    'nom' => $typeAbonnement->getNom(),
                    'exception' => $e->getMessage(),
                ]);
                $this->addFlash('error', "Erreur lors de la création du type d'abonnement.");
            }
        }

        return $this->render('type_abonnement/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_abonnement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeAbonnement $typeAbonnement): Response
    {
        $form = $this->createForm(TypeAbonnementType::class, $typeAbonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->prepareTypeAbonnement($typeAbonnement);
                $typeAbonnement->setUpdatedAt(new \DateTimeImmutable());
                $this->entityManager->flush();

                $this->addFlash('success', "Type d'abonnement mis à jour avec succès.");
                return $this->redirectToRoute('app_type_abonnement_index');
            } catch (\Exception $e) {
                $this->logger->error('Error updating subscription', [
                    'id' => $typeAbonnement->getId(),
                    'nom' => $typeAbonnement->getNom(),
                    'exception' => $e->getMessage(),
                ]);
                $this->addFlash('error', "Erreur lors de la mise à jour du type d'abonnement.");
            }
        }

        return $this->render('type_abonnement/edit.html.twig', [
            'form' => $form->createView(),
            'type_abonnement' => $typeAbonnement,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_type_abonnement_delete', methods: ['POST'])]
    public function delete(Request $request, TypeAbonnement $typeAbonnement): Response
    {
        if ($this->isCsrfTokenValid('delete' . $typeAbonnement->getId(), $request->request->get('_token'))) {
            try {
                $this->entityManager->beginTransaction();

                // Prevent deletion if there are associated reservations
                if ($typeAbonnement->getReservations()->count() > 0) {
                    $this->entityManager->rollback();
                    $this->addFlash('error', "Impossible de supprimer ce type d'abonnement car il est lié à des réservations.");
                    return $this->redirectToRoute('app_type_abonnement_index');
                }

                // Delete the TypeAbonnement
                $this->entityManager->remove($typeAbonnement);
                $this->entityManager->flush();
                $this->entityManager->commit();

                $this->addFlash('success', "Type d'abonnement supprimé avec succès.");
            } catch (\Exception $e) {
                $this->entityManager->rollback();
                $this->logger->error('Error deleting subscription', [
                    'id' => $typeAbonnement->getId(),
                    'nom' => $typeAbonnement->getNom(),
                    'exception' => $e->getMessage(),
                ]);
                $this->addFlash('error', "Erreur lors de la suppression du type d'abonnement : " . $e->getMessage());
            }
        }

        return $this->redirectToRoute('app_type_abonnement_index');
    }

    #[Route('/recommendations', name: 'app_type_abonnement_recommendations', methods: ['GET'])]
    public function recommendations(): Response
    {
        try {
            $popularSubscriptions = $this->typeAbonnementRepository->findMostReserved();
            $recommendations = $this->typeAbonnementRepository->findRecommendations();
        } catch (\Exception $e) {
            $this->logger->error('Error fetching recommendations', [
                'exception' => $e->getMessage(),
            ]);
            $this->addFlash('error', "Erreur lors de la récupération des recommandations.");
            $popularSubscriptions = $recommendations = [];
        }

        return $this->render('type_abonnement/recommendations.html.twig', [
            'popular_subscriptions' => $popularSubscriptions,
            'recommendations' => $recommendations,
        ]);
    }

    #[Route('/import-excel', name: 'app_type_abonnement_import_excel', methods: ['POST'])]
    public function importExcel(Request $request): Response
    {
        $file = $request->files->get('excel_file');
        if (!$file) {
            $this->addFlash('error', 'Aucun fichier téléchargé.');
            return $this->redirectToRoute('app_type_abonnement_index');
        }

        try {
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rowsProcessed = 0;

            foreach ($sheet->getRowIterator() as $row) {
                if ($row->getRowIndex() === 1) continue; // Skip header

                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $data = [];
                foreach ($cellIterator as $cell) {
                    $data[] = $cell ? $cell->getValue() : null;
                }

                if (empty($data[0])) continue; // Skip rows without a name

                $typeAbonnement = new TypeAbonnement();
                $typeAbonnement->setNom($data[0] ?? 'Sans nom');
                $prix = (float) ($data[1] ?? 0.00);
                if ($prix < 0) {
                    $this->logger->warning('Invalid price in import', [
                        'nom' => $data[0],
                        'prix' => $prix,
                    ]);
                    continue;
                }
                $typeAbonnement->setPrix((string) $prix);
                $duree = (int) ($data[2] ?? 1);
                if ($duree <= 0) {
                    $this->logger->warning('Invalid duration in import', [
                        'nom' => $data[0],
                        'duree' => $duree,
                    ]);
                    continue;
                }
                $typeAbonnement->setDureeEnMois($duree);
                $typeAbonnement->setIsPremium((bool) ($data[3] ?? false));
                $this->prepareTypeAbonnement($typeAbonnement);

                $this->entityManager->persist($typeAbonnement);
                $rowsProcessed++;
            }

            $this->entityManager->flush();
            $this->addFlash('success', "$rowsProcessed types d'abonnements importés avec succès.");
        } catch (\Exception $e) {
            $this->logger->error('Error importing Excel file', [
                'exception' => $e->getMessage(),
            ]);
            $this->addFlash('error', "Erreur lors de l'importation : " . $e->getMessage());
        }

        return $this->redirectToRoute('app_type_abonnement_index');
    }

    #[Route('/{id}', name: 'app_type_abonnement_show', methods: ['GET'])]
    public function show(TypeAbonnement $typeAbonnement): Response
    {
        try {
            $reservations = $this->reservationRepository->createQueryBuilder('r')
                ->where('r.typeAbonnement = :typeAbonnement')
                ->setParameter('typeAbonnement', $typeAbonnement)
                ->orderBy('r.dateDebut', 'DESC')
                ->getQuery()
                ->getResult();
        } catch (\Exception $e) {
            $this->logger->error('Error fetching reservations for TypeAbonnement', [
                'id' => $typeAbonnement->getId(),
                'nom' => $typeAbonnement->getNom(),
                'exception' => $e->getMessage(),
            ]);
            $this->addFlash('error', "Erreur lors de la récupération des réservations.");
            $reservations = [];
        }

        return $this->render('type_abonnement/show.html.twig', [
            'type_abonnement' => $typeAbonnement,
            'reservations' => $reservations,
            'reservationCount' => count($reservations),
        ]);
    }

    private function prepareTypeAbonnement(TypeAbonnement $typeAbonnement): void
    {
        if (empty($typeAbonnement->getNom())) {
            throw new \Exception('Le nom du type d\'abonnement est requis.');
        }

        if ((float) $typeAbonnement->getPrix() < 0) {
            throw new \Exception('Le prix ne peut pas être négatif.');
        }

        if ($typeAbonnement->getDureeEnMois() <= 0) {
            throw new \Exception('La durée doit être supérieure à 0.');
        }

        if (empty($typeAbonnement->getDescription())) {
            $typeAbonnement->setDescription($this->generateDescription($typeAbonnement));
        }

        $this->updatePrixReduit($typeAbonnement);
    }

    private function generateDescription(TypeAbonnement $typeAbonnement): string
    {
        $prix = $typeAbonnement->getPrixReduit() ?? $typeAbonnement->getPrix();
        return sprintf(
            "Abonnement %s à %s €, de type %s, pour une durée de %d mois, destiné à %s.",
            $typeAbonnement->getNom(),
            $prix,
            $typeAbonnement->getIsPremium() ? 'premium complète' : 'essentielle',
            $typeAbonnement->getDureeEnMois(),
            $typeAbonnement->getIsPremium() ? 'les utilisateurs exigeants' : 'un usage quotidien'
        );
    }

    private function updatePrixReduit(TypeAbonnement $typeAbonnement): void
    {
        $prixInitial = (float) $typeAbonnement->getPrix();
        $reduction = (float) $typeAbonnement->getReduction();

        if ($prixInitial < 0) {
            $this->logger->warning('Prix initial négatif, ajusté à 0', [
                'nom' => $typeAbonnement->getNom(),
            ]);
            $prixInitial = 0.0;
        }

        if ($reduction < 0 || $reduction > 100) {
            $this->logger->warning('Réduction invalide, ajustée à 0', [
                'nom' => $typeAbonnement->getNom(),
                'reduction' => $reduction,
            ]);
            $reduction = 0.0;
        }

        $prixReduit = $reduction > 0 ? $prixInitial * (1 - $reduction / 100) : null;
        $typeAbonnement->setPrixReduit($prixReduit ? number_format($prixReduit, 2, '.', '') : null);
    }
}