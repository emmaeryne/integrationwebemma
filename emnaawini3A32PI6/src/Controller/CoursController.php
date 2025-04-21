<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Users;
use App\Form\CoursType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Entity\Participant;
use App\Repository\CoursRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Postmark\PostmarkClient;
use Psr\Log\LoggerInterface;
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

    #[Route('/{user_id}', name: 'app_cours_index', methods: ['GET'])]
    public function index(int $user_id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Users::class)->find($user_id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $queryBuilder = $entityManager->getRepository(Cours::class)->createQueryBuilder('c');

        // Filtrer par rôle
        if ($user->getRole() === 'COACH') {
            $queryBuilder->where('c.id_user = :user_id')->setParameter('user_id', $user_id);
        } elseif ($user->getRole() === 'ADMIN') {
            // Pas de filtre supplémentaire pour les admins
        } else {
            // Pas de filtre supplémentaire pour les autres utilisateurs
        }

        // Recherche
        $search = $request->query->get('search');
        if ($search) {
            $queryBuilder->andWhere('c.Nom_Cours LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        // Tri
        $sort = $request->query->get('sort');
        $direction = $request->query->get('direction', 'asc');
        if ($sort) {
            // Map 'duree' to 'Duree' to match the entity field
            $sortField = $sort === 'duree' ? 'Duree' : ($sort === 'nomCours' ? 'Nom_Cours' : $sort);
            $queryBuilder->orderBy('c.' . $sortField, $direction);
        }

        $cours = $queryBuilder->getQuery()->getResult();

        return $this->render('cours/index.html.twig', [
            'cours' => $cours,
            'user' => $user,
        ]);
    }

    #[Route('/new/{user_id}', name: 'app_cours_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        int $user_id,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $entityManager->getRepository(Users::class)->find($user_id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        // Vérification du rôle COACH
        if ($user->getRole() !== 'COACH') {
            $this->addFlash('warning', 'Seuls les coachs peuvent créer des cours.');
            return $this->redirectToRoute('app_cours_index', ['user_id' => $user_id]);
        }

        $cour = new Cours();
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cour->setIdUser($user);
            $entityManager->persist($cour);
            $entityManager->flush();

            $this->addFlash('success', 'Le cours a été créé avec succès.');
            return $this->redirectToRoute('app_cours_index', ['user_id' => $user_id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cours/new.html.twig', [
            'cour' => $cour,
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/{user_id}/show/{id_cours}', name: 'app_cours_show', methods: ['GET'])]
    public function show(int $user_id, Cours $cour, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Users::class)->find($user_id);
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        return $this->render('cours/show.html.twig', [
            'cour' => $cour,
            'user' => $user,
        ]);
    }

    #[Route('/{user_id}/edit/{id_cours}', name: 'app_cours_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        int $user_id,
        Cours $cour,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $entityManager->getRepository(Users::class)->find($user_id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        // Vérification que l'utilisateur est coach ET propriétaire du cours
        if ($user->getRole() !== 'COACH' || $cour->getIdUser()->getId() !== $user_id) {
            $this->addFlash('error', 'Vous ne pouvez pas modifier ce cours.');
            return $this->redirectToRoute('app_cours_index', ['user_id' => $user_id]);
        }

        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Le cours a été mis à jour avec succès.');
            return $this->redirectToRoute('app_cours_index', ['user_id' => $user_id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cours/edit.html.twig', [
            'cour' => $cour,
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/{user_id}/delete/{id_cours}', name: 'app_cours_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        int $user_id,
        Cours $cour,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $entityManager->getRepository(Users::class)->find($user_id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        // Vérification que l'utilisateur est admin OU coach propriétaire du cours
        if (!($user->getRole() === 'ADMIN' || ($user->getRole() === 'COACH' && $cour->getIdUser()->getId() === $user_id))) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer ce cours.');
            return $this->redirectToRoute('app_cours_index', ['user_id' => $user_id]);
        }

        if ($this->isCsrfTokenValid('delete'.$cour->getIdCours(), $request->request->get('_token'))) {
            $entityManager->remove($cour);
            $entityManager->flush();
            $this->addFlash('success', 'Le cours a été supprimé avec succès.');
        }

        return $this->redirectToRoute('app_cours_index', ['user_id' => $user_id], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{user_id}/liste-participant', name: 'app_cours_liste_participant', methods: ['GET'])]
    public function listeParticipant(int $user_id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Users::class)->find($user_id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        // Vérification que l'utilisateur a le rôle USER
        if ($user->getRole() !== 'USER') {
            $this->addFlash('error', 'Seuls les utilisateurs normaux peuvent accéder à cette page.');
            return $this->redirectToRoute('app_cours_index', ['user_id' => $user_id]);
        }

        $cours = $entityManager->getRepository(Cours::class)->findAll();

        return $this->render('cours/liste_cours_participant.html.twig', [
            'cours' => $cours,
            'user' => $user,
        ]);
    }

    #[Route('/{user_id}/devenir-participant', name: 'app_devenir_participant', methods: ['POST'])]
    public function devenirParticipant(
        Request $request,
        int $user_id,
        EntityManagerInterface $entityManager
    ): Response {
        // 1. Récupérer l'utilisateur
        $user = $entityManager->getRepository(Users::class)->find($user_id);

        if (!$user) {
            $this->addFlash('error', 'Utilisateur introuvable');
            return $this->redirectToRoute('app_cours_liste_participant', ['user_id' => $user_id]);
        }

        // 2. Vérification CSRF
        if (!$this->isCsrfTokenValid('devenir_participant'.$user_id, $request->request->get('_token'))) {
            $this->addFlash('error', 'Token de sécurité invalide');
            return $this->redirectToRoute('app_cours_liste_participant', ['user_id' => $user_id]);
        }

        try {
            // 3. Vérifier si l'utilisateur est déjà participant
            $participantExist = $entityManager->getRepository(Participant::class)
                ->findOneBy(['id_user' => $user]);

            if ($participantExist) {
                $this->addFlash('warning', 'Vous êtes déjà enregistré comme participant');
                return $this->redirectToRoute('app_cours_liste_participant', ['user_id' => $user_id]);
            }

            // 4. Création du participant
            $participant = new Participant();
            $participant->setId_user($user);
            $participant->setNom($user->getUsername());
            $participant->setPrenom('');
            $participant->setAge(0);
            $participant->setAdresse('');
            $participant->setNum_telephone('');

            $entityManager->persist($participant);
            $entityManager->flush();

            $this->addFlash('success', 'Enregistrement comme participant réussi!');
        } catch (\Exception $e) {
            $this->addFlash('error', "Erreur lors de l'enregistrement: ".$e->getMessage());
        }

        return $this->redirectToRoute('app_cours_liste_participant', ['user_id' => $user_id]);
    }

    #[Route('/{id_cours}/send-email', name: 'app_cours_send_email', methods: ['GET', 'POST'])]
    public function sendEmail(Cours $cour, Request $request): Response
    {
        try {
            // Initialisation de Postmark
            $client = new PostmarkClient($this->postmarkApiKey);

            // Contenu de l'email
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

            // Envoi de l'email via Postmark
            $sendResult = $client->sendEmail(
                "manel.aloui@esprit.tn",
                "manel.aloui@esprit.tn",
                "Nouveau cours : " . $cour->getNomCours(),
                $emailContent
            );

            // Log du résultat
            $this->logger->info('Email envoyé via Postmark', [
                'result' => $sendResult,
                'to' => 'manel.aloui@esprit.tn',
            ]);

            $this->addFlash('success', 'Email envoyé avec succès !');
        } catch (\Exception $e) {
            // Log de l'erreur
            $this->logger->error('Erreur lors de l\'envoi de l\'email via Postmark', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->addFlash('error', 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_cours_show', [
            'user_id' => $cour->getIdUser()->getId(),
            'id_cours' => $cour->getIdCours()
        ]);
    }

    #[Route('/{user_id}/pdf', name: 'app_cours_pdf', methods: ['GET'])]
    public function generatePdf(int $user_id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Users::class)->find($user_id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $cours = $entityManager->getRepository(Cours::class)->findBy(['id_user' => $user_id]);

        // Configuration de Dompdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        // Générer le contenu HTML
        $html = $this->renderView('cours/pdf.html.twig', [
            'cours' => $cours,
            'user' => $user,
        ]);

        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Générer la réponse PDF
        $pdfOutput = $dompdf->output();

        return new Response($pdfOutput, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="cours.pdf"',
        ]);
    }
}
