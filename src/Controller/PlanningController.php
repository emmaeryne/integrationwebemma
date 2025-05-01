<?php
namespace App\Controller;

use App\Entity\Planning;
use App\Entity\Users;
use App\Form\PlanningType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\SvgWriter;

#[Route('/planning')]
class PlanningController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    #[Route('/', name: 'app_planning_index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->verifyCoach();

        $plannings = $this->entityManager->getRepository(Planning::class)
            ->findBy(['user' => $user]);

        return $this->render('planning/index.html.twig', [
            'plannings' => $plannings,
        ]);
    }

    #[Route('/new', name: 'app_planning_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $user = $this->verifyCoach();

        $planning = new Planning();
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planning->setUser($user);
            $this->entityManager->persist($planning);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_planning_index');
        }

        return $this->render('planning/new.html.twig', [
            'planning' => $planning,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_planning_show', methods: ['GET'])]
    public function show(Planning $planning): Response
    {
        $user = $this->verifyCoach();

        if ($planning->getUser()->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException('Accès refusé à ce planning');
        }

        return $this->render('planning/show.html.twig', [
            'planning' => $planning,
            'qrCode' => $this->generateQrCode($planning)
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'app_planning_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Planning $planning): Response
    {
        $user = $this->verifyCoach();

        if ($planning->getUser()->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException('Modification non autorisée');
        }

        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_planning_index');
        }

        return $this->render('planning/edit.html.twig', [
            'planning' => $planning,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_planning_delete', methods: ['POST'])]
    public function delete(Request $request, Planning $planning): Response
    {
        $user = $this->verifyCoach();

        if ($planning->getUser()->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException('Suppression non autorisée');
        }

        if ($this->isCsrfTokenValid('delete'.$planning->getIdPlanning(), $request->request->get('_token'))) {
            $this->entityManager->remove($planning);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_planning_index');
    }

    #[Route('/calendar', name: 'app_planning_calendar', methods: ['GET'])]
    public function calendar(): Response
    {
        $this->verifyCoach();

        return $this->render('planning/calendar.html.twig', []);
    }

    #[Route('/get-events', name: 'app_planning_get_events', methods: ['GET'])]
    public function getEvents(): JsonResponse
    {
        $user = $this->verifyCoach();

        $plannings = $this->entityManager->getRepository(Planning::class)->findBy(['user' => $user]);
        $events = [];

        foreach ($plannings as $planning) {
            if (!$planning->getDatePlanning()) {
                continue;
            }

            $endDate = (clone $planning->getDatePlanning())->modify('+1 hour');

            $events[] = [
                'id' => $planning->getIdPlanning(),
                'title' => $planning->getTypeActivite(),
                'start' => $planning->getDatePlanning()->format('Y-m-d\TH:i:s'),
                'end' => $endDate->format('Y-m-d\TH:i:s'),
                'url' => $this->generateUrl('app_planning_show', ['id' => $planning->getIdPlanning()]),
                'color' => $this->getEventColor($planning->getStatus()),
                'allDay' => false,
                'extendedProps' => [
                    'status' => $planning->getStatus(),
                    'cours' => $planning->getCours()?->getNomCours() ?? 'Non spécifié'
                ]
            ];
        }

        return $this->json($events);
    }

    #[Route('/stats', name: 'app_planning_stats', methods: ['GET'])]
    public function stats(): Response
    {
        $user = $this->verifyCoach();

        $plannings = $this->entityManager->getRepository(Planning::class)->findBy(['user' => $user]);
        $totalPlannings = count($plannings);

        $coursesData = [];
        $datesData = [];

        foreach ($plannings as $planning) {
            $courseName = $planning->getCours()?->getNomCours() ?? 'Inconnu';
            $date = $planning->getDatePlanning()?->format('Y-m-d') ?? 'Inconnue';

            $coursesData[$courseName] = ($coursesData[$courseName] ?? 0) + 1;
            $datesData[$date] = ($datesData[$date] ?? 0) + 1;
        }

        return $this->render('planning/stats.html.twig', [
            'courseLabels' => array_keys($coursesData),
            'courseValues' => array_values($coursesData),
            'dateLabels' => array_keys($datesData),
            'dateValues' => array_values($datesData),
            'totalPlannings' => $totalPlannings,
        ]);
    }

    private function verifyCoach(): Users
    {
        $user = $this->getUser();

        if (!$user instanceof Users) {
            throw $this->createNotFoundException('Utilisateur non connecté');
        }

        if ($user->getRole() !== 'COACH') {
            throw $this->createAccessDeniedException('Accès réservé aux coachs');
        }

        return $user;
    }

    private function generateQrCode(Planning $planning): string
    {
        $data = sprintf(
            "Planning ID: %d | Activité: %s | Date: %s",
            $planning->getIdPlanning(),
            $planning->getTypeActivite(),
            $planning->getDatePlanning() ? $planning->getDatePlanning()->format('Y-m-d H:i:s') : 'Pas de date'
        );

        $builder = new Builder(
            writer: new SvgWriter(),
            data: $data,
            encoding: new Encoding('UTF-8'),
            size: 300,
            margin: 10
        );

        return $builder->build()->getDataUri();
    }

    private function getEventColor(?string $status): string
    {
        return match($status) {
            'confirmé' => '#28a745',
            'annulé' => '#dc3545',
            'en attente' => '#ffc107',
            default => '#17a2b8'
        };
    }
}

