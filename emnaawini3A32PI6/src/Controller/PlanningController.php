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
use Twilio\Rest\Client;
use Endroid\QrCode\QrCode;


#[Route('/coach/{coachId<\d+>}/planning')]
class PlanningController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        //private string $twilioSid,
        //private string $twilioAuthToken,
        //private string $twilioPhoneNumber
    ) {}

    #[Route('/', name: 'app_planning_index', methods: ['GET'])]
    public function index(int $coachId): Response
    {
        $this->verifyCoach($coachId);

        $plannings = $this->entityManager->getRepository(Planning::class)
            ->findBy(['user' => $coachId]);

        return $this->render('planning/index.html.twig', [
            'plannings' => $plannings,
            'coachId' => $coachId
        ]);
    }





    #[Route('/new', name: 'app_planning_new', methods: ['GET', 'POST'])]
    public function new(Request $request, int $coachId): Response
    {
        $user = $this->verifyCoach($coachId);

        $planning = new Planning();
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planning->setUser($user);
            $this->entityManager->persist($planning);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_planning_index', ['coachId' => $coachId]);
        }

        return $this->render('planning/new.html.twig', [
            'planning' => $planning,
            'form' => $form,
            'coachId' => $coachId
        ]);
    }



    #[Route('/{id<\d+>}', name: 'app_planning_show', methods: ['GET'])]
    public function show(int $coachId, Planning $planning): Response
    {
        $this->verifyCoach($coachId);

        if ($planning->getUser()->getId() !== $coachId) {
            throw $this->createAccessDeniedException('Accès refusé à ce planning');
        }

        return $this->render('planning/show.html.twig', [
            'planning' => $planning,
            'coachId' => $coachId,
            'qrCode' => $this->generateQrCode($planning)
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'app_planning_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $coachId, Planning $planning): Response
    {
        $this->verifyCoach($coachId);

        if ($planning->getUser()->getId() !== $coachId) {
            throw $this->createAccessDeniedException('Modification non autorisée');
        }

        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_planning_index', ['coachId' => $coachId]);
        }

        return $this->render('planning/edit.html.twig', [
            'planning' => $planning,
            'form' => $form,
            'coachId' => $coachId
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_planning_delete', methods: ['POST'])]
    public function delete(Request $request, int $coachId, Planning $planning): Response
    {
        $this->verifyCoach($coachId);

        if ($planning->getUser()->getId() !== $coachId) {
            throw $this->createAccessDeniedException('Suppression non autorisée');
        }

        if ($this->isCsrfTokenValid('delete'.$planning->getIdPlanning(), $request->request->get('_token'))) {
            $this->entityManager->remove($planning);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_planning_index', ['coachId' => $coachId]);
    }

    #[Route('/calendar', name: 'app_planning_calendar', methods: ['GET'])]
    public function calendar(): Response
    {
        return $this->render('planning/calendar.html.twig');
    }

    #[Route('/get-events', name: 'app_planning_get_events', methods: ['GET'])]
    public function getEvents(): JsonResponse
    {
        $plannings = $this->entityManager->getRepository(Planning::class)->findAll();
        $events = [];

        foreach ($plannings as $planning) {
            if (!$planning->getDatePlanning()) {
                continue;
            }

            $endDate = (clone $planning->getDatePlanning())->modify('+1 hour');

            $events[] = [
                'id' => $planning->getId(),
                'title' => $planning->getTypeActivite(),
                'start' => $planning->getDatePlanning()->format('Y-m-d\TH:i:s'),
                'end' => $endDate->format('Y-m-d\TH:i:s'),
                'url' => $this->generateUrl('app_planning_show', [
                    'coachId' => $planning->getUser()->getId(),
                    'id' => $planning->getId()
                ]),
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
    /*#[Route('/send-sms/{id<\d+>}', name: 'send_sms', methods: [' POST'])]
    public function sendSms(Planning $planning): Response
    {
        if (empty($this->twilioSid) || empty($this->twilioAuthToken) || empty($this->twilioPhoneNumber)) {
            $this->addFlash('warning', 'Configuration SMS incomplète - le SMS n\'a pas été envoyé');
            return $this->redirectToRoute('app_planning_index', ['coachId' => $planning->getUser()->getId()]);
        }

        $recipientNumber = '+21694194203';

        try {
            $client = new Client($this->twilioSid, $this->twilioAuthToken);

            $activity = $planning->getTypeActivite() ?? 'Activité non spécifiée';
            $date = $planning->getDatePlanning()?->format('d/m/Y H:i') ?? 'Date non définie';
            $course = $planning->getCours()?->getNomCours() ?? 'Cours non spécifié';
            $status = $planning->getStatus() ?? 'Statut non défini';

            $message = sprintf(
                "Nouveau planning créé :\n\nActivité: %s\nDate: %s\nCours: %s\nStatut: %s\n\nMerci de votre confiance !",
                $activity, $date, $course, $status
            );

            $client->messages->create($recipientNumber, [
                'from' => $this->twilioPhoneNumber,
                'body' => $message
            ]);

            $this->addFlash('success', 'SMS envoyé avec succès à ' . $recipientNumber);
        } catch (\Exception $e) {
            $this->addFlash('warning', 'L\'envoi du SMS a échoué. Erreur: ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_planning_index', ['coachId' => $planning->getUser()->getId()]);
    }*/


    #[Route('/stats', name: 'app_planning_stats', methods: ['GET'])]
    public function stats(int $coachId): Response
    {
        $this->verifyCoach($coachId);

        $plannings = $this->entityManager->getRepository(Planning::class)->findBy(['user' => $coachId]);
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
            'coachId' => $coachId
        ]);
    }

    private function verifyCoach(int $coachId): Users
    {
        $user = $this->entityManager->getRepository(Users::class)->find($coachId);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
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
    /*#[Route('/send-sms', name: 'send_sms', methods: ['POST'])]
    public function sendSms(Request $request): Response
    {
        $planningId = $request->request->get('planning_id');
        $planning = $this->entityManager->getRepository(Planning::class)->find($planningId);

        if (!$planning) {
            return $this->json(['error' => 'Planning non trouvé'], Response::HTTP_NOT_FOUND);
        }

        if (empty($this->twilioSid) || empty($this->twilioAuthToken) || empty($this->twilioPhoneNumber)) {
            return $this->json(['error' => 'Configuration SMS incomplète'], Response::HTTP_BAD_REQUEST);
        }

        $recipientNumber = '+21641745962'; // Remplacez par le numéro correct

        try {
            $client = new Client($this->twilioSid, $this->twilioAuthToken);
            $message = sprintf("Nouveau planning créé :\nActivité: %s\nDate: %s",
                $planning->getTypeActivite(),
                $planning->getDatePlanning() ? $planning->getDatePlanning()->format('d/m/Y H:i') : 'Date non définie'
            );

            $client->messages->create(
                $recipientNumber,
                [
                    'from' => $this->twilioPhoneNumber,
                    'body' => $message,
                ]
            );

            return $this->json(['success' => 'SMS envoyé avec succès à ' . $recipientNumber]);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de l\'envoi du SMS: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }*/
    /*#[Route('/send-planning-sms/{id<\d+>}', name: 'send_planning_sms', methods: ['POST'])]
    public function sendPlanningSms(Request $request, Planning $planning): Response
    {
        // Valider le jeton CSRF
        if (!$this->isCsrfTokenValid('send_planning_sms' . $planning->getIdPlanning(), $request->request->get('_token'))) {
            $this->addFlash('warning', 'Jeton CSRF invalide.');
            return $this->redirectToRoute('app_planning_show', ['coachId' => $planning->getUser()->getId(), 'id' => $planning->getIdPlanning()]);
        }

        // Vérifier la configuration Twilio
        if (empty($this->twilioSid) || empty($this->twilioAuthToken) || empty($this->twilioPhoneNumber)) {
            $this->addFlash('warning', 'Configuration SMS incomplète - le SMS n\'a pas été envoyé');
            return $this->redirectToRoute('app_planning_show', ['coachId' => $planning->getUser()->getId(), 'id' => $planning->getIdPlanning()]);
        }

        // Définir le numéro destinataire (remplacez par un numéro différent pour les tests)
        $recipientNumber = '+21641745962'; // TODO: Remplacez par un numéro vérifié différent de TWILIO_PHONE_NUMBER
        // Alternative si Users a un champ phoneNumber :
        // $recipientNumber = $planning->getUser()->getPhoneNumber();
        // if (!$recipientNumber) {
        //     $this->addFlash('warning', 'Aucun numéro de téléphone défini pour l\'utilisateur.');
        //     return $this->redirectToRoute('app_planning_show', ['coachId' => $planning->getUser()->getId(), 'id' => $planning->getId()]);
        // }

        // Vérifier que les numéros From et To sont différents
        if ($recipientNumber === $this->twilioPhoneNumber) {
            $this->addFlash('warning', 'Le numéro destinataire est identique au numéro d\'envoi. SMS non envoyé.');
            return $this->redirectToRoute('app_planning_show', ['coachId' => $planning->getUser()->getId(), 'id' => $planning->getIdPlanning()]);
        }

        try {
            // Initialiser le client Twilio
            $client = new Client($this->twilioSid, $this->twilioAuthToken);

            // Préparer le contenu du message
            $activity = $planning->getTypeActivite() ?? 'Activité non spécifiée';
            $date = $planning->getDatePlanning()?->format('d/m/Y H:i') ?? 'Date non définie';
            $course = $planning->getCours()?->getNomCours() ?? 'Cours non spécifié';
            $status = $planning->getStatus() ?? 'Statut non défini';

            $messageContent = sprintf(
                "Détails du planning:\n\nActivité: %s\nDate: %s\nCours: %s\nStatut: %s\n\nMerci de votre confiance !",
                $activity,
                $date,
                $course,
                $status
            );

            // Envoyer le SMS
            $client->messages->create($recipientNumber, [
                'from' => $this->twilioPhoneNumber,
                'body' => $messageContent
            ]);

            $this->addFlash('success', 'SMS envoyé avec succès à ' . $recipientNumber);
        } catch (\Exception $e) {
            $this->addFlash('warning', 'L\'envoi du SMS a échoué. Erreur: ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_planning_show', ['coachId' => $planning->getUser()->getId(), 'id' => $planning->getIdPlanning()]);
    }*/




}

