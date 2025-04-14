<?php
/*namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Users;
use App\Form\CoursType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/cours')]
class CoursController extends AbstractController
{
    #[Route('/{user_id}', name: 'app_cours_index', methods: ['GET'])]
    public function index(int $user_id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Users::class)->find($user_id);
        
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        if ($user->getRole() === 'COACH') {
            $cours = $entityManager->getRepository(Cours::class)->findBy(['id_user' => $user_id]);
            return $this->render('cours/index.html.twig', [
                'cours' => $cours,
                'user' => $user,
            ]);
        } elseif ($user->getRole() === 'ADMIN') {
            $cours = $entityManager->getRepository(Cours::class)->findAll();
            return $this->render('cours/liste_cours_admin.html.twig', [
                'cours' => $cours,
                'user' => $user,
            ]);
        } else {
            $cours = $entityManager->getRepository(Cours::class)->findAll();
            return $this->render('cours/user_list.html.twig', [
                'cours' => $cours,
                'user' => $user,
            ]);
        }
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
}*/

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
use App\Entity\Participant;  // Ajoutez cette ligne avec les autres use statements

#[Route('/cours')]
class CoursController extends AbstractController
{
    #[Route('/{user_id}', name: 'app_cours_index', methods: ['GET'])]
    public function index(int $user_id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Users::class)->find($user_id);
        
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        if ($user->getRole() === 'COACH') {
            $cours = $entityManager->getRepository(Cours::class)->findBy(['id_user' => $user_id]);
            return $this->render('cours/index.html.twig', [
                'cours' => $cours,
                'user' => $user,
            ]);
        } elseif ($user->getRole() === 'ADMIN') {
            $cours = $entityManager->getRepository(Cours::class)->findAll();
            return $this->render('cours/liste_cours_admin.html.twig', [
                'cours' => $cours,
                'user' => $user,
            ]);
        } else {
            $cours = $entityManager->getRepository(Cours::class)->findAll();
            return $this->render('cours/user_list.html.twig', [
                'cours' => $cours,
                'user' => $user,
            ]);
        }
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

        // 4. Création du participant (en utilisant les noms exacts des méthodes de votre entité)
        $participant = new Participant();
        $participant->setId_user($user); // Notez le underscore
        $participant->setNom($user->getUsername());
        $participant->setPrenom(''); 
        $participant->setAge(0);
        $participant->setAdresse('');
        $participant->setNum_telephone(''); // Notez le underscore

        $entityManager->persist($participant);
        $entityManager->flush();

        $this->addFlash('success', 'Enregistrement comme participant réussi!');
    } catch (\Exception $e) {
        $this->addFlash('error', "Erreur lors de l'enregistrement: ".$e->getMessage());
    }

    return $this->redirectToRoute('app_cours_liste_participant', ['user_id' => $user_id]);
}
}



