<?php
namespace App\Controller;

use App\Entity\GameMatch;
use App\Entity\Tournoi;
use App\Form\TournoiType;
use App\Repository\EquipeRepository;
use App\Repository\TournoiRepository;
use App\Repository\TerrainRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TournoiController extends AbstractController
{
    #[Route('/tournoi', name: 'app_tournoi_index', methods: ['GET'])]
    public function index(TournoiRepository $tournoiRepository): Response
    {
        $tournois = $tournoiRepository->findAll();

        if (!$tournois) {
            $this->addFlash('error', 'No tournaments found.');
        }

        return $this->render('tournoi/index.html.twig', [
            'tournois' => $tournois,
        ]);
    }

    #[Route('/new', name: 'app_tournoi_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tournoi = new Tournoi();
        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tournoi);
            $entityManager->flush();

            return $this->redirectToRoute('app_tournoi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tournoi/new.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form,
        ]);
    }

    #[Route('/{idTournoi<\d+>}', name: 'app_tournoi_show', methods: ['GET'])]
    public function show(int $idTournoi, TournoiRepository $tournoiRepository): Response
    {
        $tournoi = $tournoiRepository->find($idTournoi);

        if (!$tournoi) {
            throw $this->createNotFoundException('Tournoi not found.');
        }

        $matches = $tournoi->getMatchs();

        return $this->render('tournoi/show.html.twig', [
            'tournoi' => $tournoi,
            'matches' => $matches,
        ]);
    }

    #[Route('/{id_tournoi}/edit', name: 'app_tournoi_edit', methods: ['GET', 'POST'])]
    public function edit(
        int $id_tournoi,
        Request $request,
        TournoiRepository $tournoiRepository,
        EntityManagerInterface $entityManager,
        EquipeRepository $equipeRepository,
        TerrainRepository $terrainRepository
    ): Response {
        $tournoi = $tournoiRepository->find($id_tournoi);

        if (!$tournoi) {
            throw $this->createNotFoundException('The tournoi does not exist.');
        }

        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Tournoi updated successfully!');
            return $this->redirectToRoute('app_tournoi_index', [], Response::HTTP_SEE_OTHER);
        }

        $equipes = $equipeRepository->findAll();
        $terrains = $terrainRepository->findAll();
        $matches = $tournoi->getMatchs(); 

        return $this->render('tournoi/edit.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form,
            'equipes' => $equipes,
            'terrains' => $terrains,
            'matches' => $matches,
        ]);
    }

    #[Route('/{id_tournoi}', name: 'app_tournoi_delete', methods: ['POST'])]
    public function delete(Request $request, int $id_tournoi, TournoiRepository $tournoiRepository, EntityManagerInterface $entityManager): Response
    {
        $tournoi = $tournoiRepository->find($id_tournoi);

        if (!$tournoi) {
            throw $this->createNotFoundException('The tournoi does not exist.');
        }

        if ($this->isCsrfTokenValid('delete' . $tournoi->getIdTournoi(), $request->request->get('_token'))) {
            $entityManager->remove($tournoi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tournoi_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id_tournoi}/add-match', name: 'app_tournoi_add_match', methods: ['POST'])]
    public function addMatch(
        int $id_tournoi,
        Request $request,
        TournoiRepository $tournoiRepository,
        EquipeRepository $equipeRepository,
        TerrainRepository $terrainRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $tournoi = $tournoiRepository->find($id_tournoi);

        if (!$tournoi) {
            throw $this->createNotFoundException('The tournoi does not exist.');
        }

        $idEquipe1 = $request->request->get('id_equipe1');
        $idEquipe2 = $request->request->get('id_equipe2');
        $idTerrain = $request->request->get('id_terrain');
        $dateMatch = $request->request->get('date_match');

        $equipe1 = $equipeRepository->find($idEquipe1);
        $equipe2 = $equipeRepository->find($idEquipe2);
        $terrain = $terrainRepository->find($idTerrain);

        if (!$equipe1 || !$equipe2 || !$terrain) {
            $this->addFlash('error', 'Invalid team or terrain selection.');
            return $this->redirectToRoute('app_tournoi_edit', ['id_tournoi' => $id_tournoi]);
        }

        $gameMatch = new GameMatch();
        $gameMatch->setTournoi($tournoi)
            ->setEquipe1($equipe1)
            ->setEquipe2($equipe2)
            ->setTerrain($terrain)
            ->setDateMatch(new \DateTime($dateMatch))
            ->setScoreEquipe1(0)
            ->setScoreEquipe2(0)
            ->setStatutMatch('Scheduled');

        $entityManager->persist($gameMatch);
        $entityManager->flush();

        $this->addFlash('success', 'Match added to the tournament successfully.');
        return $this->redirectToRoute('app_tournoi_edit', ['id_tournoi' => $id_tournoi]);
    }
}
