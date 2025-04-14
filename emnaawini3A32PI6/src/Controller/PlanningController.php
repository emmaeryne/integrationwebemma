<?php

/*namespace App\Controller;

use App\Entity\Planning;
use App\Form\PlanningType;
use App\Repository\PlanningRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/planning')]
class PlanningController extends AbstractController
{
    #[Route('/', name: 'app_planning_index', methods: ['GET'])]
    public function index(PlanningRepository $planningRepository): Response
    {
        return $this->render('planning/index.html.twig', [
            'plannings' => $planningRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_planning_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $planning = new Planning();
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($planning);
            $entityManager->flush();

            return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planning/new.html.twig', [
            'planning' => $planning,
            'form' => $form,
        ]);
    }

    #[Route('/{idPlanning}', name: 'app_planning_show', methods: ['GET'])]
    public function show(Planning $planning): Response
    {
        return $this->render('planning/show.html.twig', [
            'planning' => $planning,
        ]);
    }

    #[Route('/{idPlanning}/edit', name: 'app_planning_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Planning $planning,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planning/edit.html.twig', [
            'planning' => $planning,
            'form' => $form,
        ]);
    }

    #[Route('/{idPlanning}', name: 'app_planning_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Planning $planning,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$planning->getIdPlanning(), $request->request->get('_token'))) {
            $entityManager->remove($planning);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
    }
}*/








namespace App\Controller;

use App\Entity\Planning;
use App\Entity\Users;
use App\Form\PlanningType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/coach/{coachId}/planning')]
class PlanningController extends AbstractController
{
    #[Route('/', name: 'app_planning_index', methods: ['GET'])]
    public function index(int $coachId, EntityManagerInterface $em): Response
    {
        $this->verifyCoach($coachId, $em);
        
        $plannings = $em->getRepository(Planning::class)
                       ->findBy(['user' => $coachId]);

        return $this->render('planning/index.html.twig', [
            'plannings' => $plannings,
            'coachId' => $coachId
        ]);
    }

    #[Route('/new', name: 'app_planning_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        int $coachId,
        EntityManagerInterface $em
    ): Response {
        $user = $this->verifyCoach($coachId, $em);

        $planning = new Planning();
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planning->setUser($user);
            $em->persist($planning);
            $em->flush();

            return $this->redirectToRoute('app_planning_index', [
                'coachId' => $coachId
            ]);
        }

        return $this->render('planning/new.html.twig', [
            'planning' => $planning,
            'form' => $form,
            'coachId' => $coachId
        ]);
    }

    #[Route('/{id}', name: 'app_planning_show', methods: ['GET'])]
    public function show(int $coachId, Planning $planning, EntityManagerInterface $em): Response
    {
        $this->verifyCoach($coachId, $em);
        
        if ($planning->getUser()->getId() !== $coachId) {
            throw $this->createAccessDeniedException('Accès refusé à ce planning');
        }

        return $this->render('planning/show.html.twig', [
            'planning' => $planning,
            'coachId' => $coachId
        ]);
    }

    #[Route('/{id}/edit', name: 'app_planning_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        int $coachId,
        Planning $planning,
        EntityManagerInterface $em
    ): Response {
        $this->verifyCoach($coachId, $em);
        
        if ($planning->getUser()->getId() !== $coachId) {
            throw $this->createAccessDeniedException('Modification non autorisée');
        }

        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_planning_index', [
                'coachId' => $coachId
            ]);
        }

        return $this->render('planning/edit.html.twig', [
            'planning' => $planning,
            'form' => $form,
            'coachId' => $coachId
        ]);
    }

    #[Route('/{id}', name: 'app_planning_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        int $coachId,
        Planning $planning,
        EntityManagerInterface $em
    ): Response {
        $this->verifyCoach($coachId, $em);
        
        if ($planning->getUser()->getId() !== $coachId) {
            throw $this->createAccessDeniedException('Suppression non autorisée');
        }

        if ($this->isCsrfTokenValid('delete'.$planning->getIdPlanning(), $request->request->get('_token'))) {
            $em->remove($planning);
            $em->flush();
        }

        return $this->redirectToRoute('app_planning_index', [
            'coachId' => $coachId
        ]);
    }

    private function verifyCoach(int $coachId, EntityManagerInterface $em): Users
    {
        $user = $em->getRepository(Users::class)->find($coachId);
        
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        if ($user->getRole() !== 'COACH') {
            throw $this->createAccessDeniedException('Accès réservé aux coachs');
        }

        return $user;
    }
}