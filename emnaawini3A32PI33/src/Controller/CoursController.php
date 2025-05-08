<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Users;
use App\Entity\Participant;
use App\Entity\Cours_participant;
use App\Form\CoursType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Postmark\PostmarkClient;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/cours')]
class CoursController extends AbstractController
{
    private LoggerInterface $logger;
    private string $postmarkApiKey;

    public function __construct(LoggerInterface $logger, string $postmarkApiKey)
    {
        $this->logger = $logger;
        $this->postmarkApiKey = $postmarkApiKey;
    }

    #[Route('/', name: 'app_home1', methods: ['GET'])]
    public function home(EntityManagerInterface $entityManager): Response
    {
        $cours = $entityManager->getRepository(Cours::class)->findAll();

        return $this->render('gym-html-template/home.html.twig', [
            'cours' => $cours,
        ]);
    }

    #[Route('/index', name: 'app_cours_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Users) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }

        // Fetch all courses with their participants
        $queryBuilder = $entityManager->getRepository(Cours::class)->createQueryBuilder('c')
            ->leftJoin('c.cours_participants', 'cp')
            ->addSelect('cp');

        // Handle search functionality
        $search = $request->query->get('search');
        if ($search) {
            $queryBuilder->andWhere('c.Nom_Cours LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        // Handle sorting
        $sort = $request->query->get('sort');
        $direction = $request->query->get('direction', 'asc');
        if ($sort) {
            $sortField = $sort === 'duree' ? 'Duree' : ($sort === 'nomCours' ? 'Nom_Cours' : $sort);
            $queryBuilder->orderBy('c.' . $sortField, $direction);
        }

        $cours = $queryBuilder->getQuery()->getResult();

        return $this->render('cours/index.html.twig', [
            'cours' => $cours,
            'user' => $user,
        ]);
    }

    #[Route('/new', name: 'app_cours_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Users) {
            $this->addFlash('error', 'Vous devez être connecté pour créer un cours.');
            return $this->redirectToRoute('app_login');
        }

        if ($user->getRole() !== 'COACH') {
            $this->addFlash('warning', 'Seuls les coachs peuvent créer des cours.');
            return $this->redirectToRoute('app_cours_index');
        }

        $cour = new Cours();
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cour->setIdUser($user);
            $entityManager->persist($cour);
            $entityManager->flush();

            $this->addFlash('success', 'Le cours a été créé avec succès.');
            return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cours/new.html.twig', [
            'cour' => $cour,
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/show/{id_cours}', name: 'app_cours_show', methods: ['GET'])]
    public function show(Cours $cour, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Users) {
            $this->addFlash('error', 'Vous devez être connecté pour voir ce cours.');
            return $this->redirectToRoute('app_login');
        }

        if ($user->getRole() === 'COACH' && $cour->getIdUser()->getId() !== $user->getId()) {
            $this->addFlash('error', 'Vous ne pouvez pas voir ce cours.');
            return $this->redirectToRoute('app_cours_index');
        }

        return $this->render('cours/show.html.twig', [
            'cour' => $cour,
            'user' => $user,
        ]);
    }

    #[Route('/edit/{id_cours}', name: 'app_cours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cours $cour, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Users) {
            $this->addFlash('error', 'Vous devez être connecté pour modifier un cours.');
            return $this->redirectToRoute('app_login');
        }

        if ($user->getRole() !== 'COACH' || $cour->getIdUser()->getId() !== $user->getId()) {
            $this->addFlash('error', 'Vous ne pouvez pas modifier ce cours.');
            return $this->redirectToRoute('app_cours_index');
        }

        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Le cours a été mis à jour avec succès.');
            return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cours/edit.html.twig', [
            'cour' => $cour,
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/delete/{id_cours}', name: 'app_cours_delete', methods: ['POST'])]
    public function delete(Request $request, Cours $cour, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Users) {
            $this->addFlash('error', 'Vous devez être connecté pour supprimer un cours.');
            return $this->redirectToRoute('app_login');
        }

        if (!($user->getRole() === 'ADMIN' || ($user->getRole() === 'COACH' && $cour->getIdUser()->getId() === $user->getId()))) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer ce cours.');
            return $this->redirectToRoute('app_cours_index');
        }

        if ($this->isCsrfTokenValid('delete' . $cour->getIdCours(), $request->request->get('_token'))) {
            $entityManager->remove($cour);
            $entityManager->flush();
            $this->addFlash('success', 'Le cours a été supprimé avec succès.');
        }

        return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/liste-participant', name: 'app_cours_liste_participant', methods: ['GET'])]
    public function listeParticipant(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Users) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }

        // Fetch all courses with their participants
        $cours = $entityManager->getRepository(Cours::class)->createQueryBuilder('c')
            ->leftJoin('c.cours_participants', 'cp')
            ->leftJoin('cp.id_participant', 'p')
            ->addSelect('cp', 'p')
            ->getQuery()
            ->getResult();

        return $this->render('cours/liste_cours_participant.html.twig', [
            'cours' => $cours,
            'user' => $user,
        ]);
    }

    #[Route('/{id_cours}/send-email', name: 'app_cours_send_email', methods: ['GET', 'POST'])]
    public function sendEmail(Cours $cour, Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Users) {
            $this->addFlash('error', 'Vous devez être connecté pour envoyer un email.');
            return $this->redirectToRoute('app_login');
        }

        if ($user->getRole() === 'COACH' && $cour->getIdUser()->getId() !== $user->getId()) {
            $this->addFlash('error', 'Vous ne pouvez pas envoyer un email pour ce cours.');
            return $this->redirectToRoute('app_cours_index');
        }

        try {
            $client = new PostmarkClient($this->postmarkApiKey);
            $emailContent = sprintf(
                "Détails du cours:\n\n" .
                "ID: %s\n" .
                "Nom: %s\n" .
                "Durée: %s minutes\n\n" .
                "Merci de votre participation !",
                $cour->getIdCours(),
                $cour->getNomCours(),
                $cour->getDuree()
            );

            $sendResult = $client->sendEmail(
                "manel.aloui@esprit.tn",
                "manel.aloui@esprit.tn",
                "Nouveau cours : " . $cour->getNomCours(),
                $emailContent
            );

            $this->logger->info('Email envoyé via Postmark', [
                'result' => $sendResult,
                'to' => 'manel.aloui@esprit.tn',
            ]);

            $this->addFlash('success', 'Email envoyé avec succès !');
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'envoi de l\'email via Postmark', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->addFlash('error', 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_cours_show', [
            'id_cours' => $cour->getIdCours()
        ]);
    }

    #[Route('/pdf', name: 'app_cours_pdf', methods: ['GET'])]
    public function generatePdf(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Users) {
            $this->addFlash('error', 'Vous devez être connecté pour générer un PDF.');
            return $this->redirectToRoute('app_login');
        }

        $cours = $entityManager->getRepository(Cours::class)->findBy(['id_user' => $user->getId()]);

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        $html = $this->renderView('cours/pdf.html.twig', [
            'cours' => $cours,
            'user' => $user,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfOutput = $dompdf->output();

        return new Response($pdfOutput, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="cours.pdf"',
        ]);
    }

    #[Route('/{id_cours}/devenir-participant', name: 'app_devenir_participant', methods: ['POST'])]
    public function devenirParticipant(
        Request $request,
        int $id_cours,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        if (!$user instanceof Users) {
            $this->addFlash('error', 'Vous devez être connecté pour participer à un cours.');
            return $this->redirectToRoute('app_login');
        }

        $cours = $entityManager->getRepository(Cours::class)->find($id_cours);
        if (!$cours) {
            $this->addFlash('error', 'Cours introuvable.');
            return $this->redirectToRoute('app_cours_liste_participant');
        }

        if (!$this->isCsrfTokenValid('devenir_participant' . $id_cours, $request->request->get('_token'))) {
            $this->addFlash('error', 'Token de sécurité invalide.');
            return $this->redirectToRoute('app_cours_liste_participant');
        }

        try {
            $participant = $entityManager->getRepository(Participant::class)
                ->findOneBy(['id_user' => $user]);

            if (!$participant) {
                $participant = new Participant();
                $participant->setIdUser($user);
                $participant->setNom($user->getUsername());
                $participant->setPrenom('');
                $participant->setAge(0);
                $participant->setAdresse('');
                $participant->setNumTelephone('');
                $entityManager->persist($participant);
            }

            $participantCount = $entityManager->getRepository(Cours_participant::class)
                ->createQueryBuilder('cp')
                ->select('COUNT(cp)')
                ->where('cp.id_cours = :cours')
                ->setParameter('cours', $cours)
                ->getQuery()
                ->getSingleScalarResult();

            if ($participantCount >= 2) {
                $this->addFlash('error', 'Ce cours est complet (2 participants maximum).');
                return $this->redirectToRoute('app_cours_liste_participant');
            }

            $existingCoursParticipant = $entityManager->getRepository(Cours_participant::class)
                ->findOneBy(['id_participant' => $participant, 'id_cours' => $cours]);

            if ($existingCoursParticipant) {
                $this->addFlash('warning', 'Vous êtes déjà inscrit à ce cours.');
                return $this->redirectToRoute('app_cours_liste_participant');
            }

            $coursParticipant = new Cours_participant();
            $coursParticipant->setId_participant($participant);
            $coursParticipant->setCours($cours);

            $entityManager->persist($coursParticipant);
            $entityManager->flush();

            $this->addFlash('success', 'Inscription au cours réussie !');
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'inscription au cours', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->addFlash('error', 'Erreur lors de l\'inscription : ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_cours_liste_participant');
    }
}