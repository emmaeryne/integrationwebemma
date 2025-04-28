<?php

namespace App\Controller;

use App\Entity\GameMatch;
use App\Repository\GameMatchRepository;
use App\Form\GameMatchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
// Removed duplicate import to resolve naming conflict

#[Route('/match')]
final class GameMatchController extends AbstractController
{
    #[Route(name: 'app_game_match_index', methods: ['GET'])]
    public function index(GameMatchRepository $gameMatchRepository): Response
    {
        return $this->render('game_match/index.html.twig', [
            'game_matches' => $gameMatchRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_game_match_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $gameMatch = new GameMatch();
        $form = $this->createForm(GameMatchType::class, $gameMatch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($gameMatch);
            $entityManager->flush();

            return $this->redirectToRoute('app_game_match_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('game_match/new.html.twig', [
            'game_match' => $gameMatch,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_game_match_show', methods: ['GET'])]
    public function show(GameMatch $gameMatch): Response
    {
        return $this->render('game_match/show.html.twig', [
            'game_match' => $gameMatch,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_game_match_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, GameMatch $gameMatch, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GameMatchType::class, $gameMatch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_game_match_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('game_match/edit.html.twig', [
            'game_match' => $gameMatch,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_game_match_delete', methods: ['POST'])]
    public function delete(Request $request, GameMatch $gameMatch, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $gameMatch->getId(), $request->request->get('_token'))) {
            $entityManager->remove($gameMatch);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_game_match_index', [], Response::HTTP_SEE_OTHER);
    }
}
