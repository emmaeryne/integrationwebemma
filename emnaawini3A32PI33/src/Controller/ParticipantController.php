<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Users;
use App\Form\ParticipantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/participant')]
final class ParticipantController extends AbstractController
{
    #[Route('/{userId}/index', name: 'app_participant_index', methods: ['GET'])]
    public function index(int $userId, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Users::class)->find($userId);
        if (!$user || $user->getRole() !== 'COACH') {
            throw new AccessDeniedException('Vous n\'avez pas les droits nécessaires pour accéder à cette page.');
        }

        $participants = $entityManager
            ->getRepository(Participant::class)
            ->findAll();

        return $this->render('participant/index.html.twig', [
            'participants' => $participants,
            'userId' => $userId, // Passer l'ID de l'utilisateur au template
        ]);
    }

    #[Route('/{userId}/new', name: 'app_participant_new', methods: ['GET', 'POST'])]
    public function new(int $userId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Users::class)->find($userId);
        if (!$user || $user->getRole() !== 'COACH') {
            throw new AccessDeniedException('Vous n\'avez pas les droits nécessaires pour accéder à cette page.');
        }

        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participant);
            $entityManager->flush();

            return $this->redirectToRoute('app_participant_index', ['userId' => $userId], Response::HTTP_SEE_OTHER);
        }

        return $this->render('participant/new.html.twig', [
            'participant' => $participant,
            'form' => $form,
        ]);
    }

    #[Route('/{userId}/{id}', name: 'app_participant_show', methods: ['GET'])]
    public function show(int $userId, Participant $participant, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Users::class)->find($userId);
        if (!$user || $user->getRole() !== 'COACH') {
            throw new AccessDeniedException('Vous n\'avez pas les droits nécessaires pour accéder à cette page.');
        }

        return $this->render('participant/show.html.twig', [
            'participant' => $participant,
        ]);
    }

    #[Route('/{userId}/{id}/edit', name: 'app_participant_edit', methods: ['GET', 'POST'])]
    public function edit(int $userId, Request $request, Participant $participant, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Users::class)->find($userId);
        if (!$user || $user->getRole() !== 'COACH') {
            throw new AccessDeniedException('Vous n\'avez pas les droits nécessaires pour accéder à cette page.');
        }

        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_participant_index', ['userId' => $userId], Response::HTTP_SEE_OTHER);
        }

        return $this->render('participant/edit.html.twig', [
            'participant' => $participant,
            'form' => $form,
        ]);
    }

    #[Route('/{userId}/{id}', name: 'app_participant_delete', methods: ['POST'])]
    public function delete(int $userId, Request $request, Participant $participant, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Users::class)->find($userId);
        if (!$user || $user->getRole() !== 'COACH') {
            throw new AccessDeniedException('Vous n\'avez pas les droits nécessaires pour accéder à cette page.');
        }

        if ($this->isCsrfTokenValid('delete'.$participant->getId(), $request->request->get('_token'))) {
            $entityManager->remove($participant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_participant_index', ['userId' => $userId], Response::HTTP_SEE_OTHER);
    }
}


