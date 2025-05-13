<?php

namespace App\Controller;

use App\Entity\GameMatch;
use App\Entity\Tournoi;
use App\Entity\Terrain;
use App\Entity\StatistiquesEquipe;
use App\Form\GameMatchType;
use App\Repository\EquipeRepository;
use App\Repository\GameMatchRepository;
use App\Service\StatistiquesEquipeUpdater;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/match')]
final class GameMatchController extends AbstractController
{
    private StatistiquesEquipeUpdater $statistiquesEquipeUpdater;
    private EquipeRepository $equipeRepository;

    public function __construct(StatistiquesEquipeUpdater $statistiquesEquipeUpdater, EquipeRepository $equipeRepository)
    {
        $this->statistiquesEquipeUpdater = $statistiquesEquipeUpdater;
        $this->equipeRepository = $equipeRepository;
    }

    #[Route(name: 'app_game_match_index', methods: ['GET'])]
    public function index(Request $request, GameMatchRepository $gameMatchRepository, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $gameMatchRepository->createQueryBuilder('gm')
            ->leftJoin('gm.equipe1', 'e1')
            ->leftJoin('gm.equipe2', 'e2')
            ->addSelect('e1', 'e2');

        if ($team = $request->query->get('team')) {
            $queryBuilder->andWhere('e1.nom_equipe LIKE :team OR e2.nom_equipe LIKE :team')
                ->setParameter('team', '%' . $team . '%');
        }

        if ($status = $request->query->get('status')) {
            $queryBuilder->andWhere('gm.statut_match = :status')
                ->setParameter('status', $status);
        }

        if ($date = $request->query->get('date')) {
            $queryBuilder->andWhere('gm.dateMatch = :date')
                ->setParameter('date', $date);
        }

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('game_match/index.html.twig', [
            'game_matches' => $pagination,
        ]);
    }

    #[Route('/export', name: 'app_game_match_export', methods: ['GET'])]
    public function export(GameMatchRepository $gameMatchRepository): Response
    {
        $matches = $gameMatchRepository->findAll();

        $csvContent = "Match ID,Date,Team 1,Team 1 Score,Team 2,Team 2 Score,Status\n";
        foreach ($matches as $match) {
            $csvContent .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s\n",
                $match->getIdMatch(),
                $match->getDateMatch() ? $match->getDateMatch()->format('Y-m-d') : 'N/A',
                $match->getEquipe1()->getNomEquipe(),
                $match->getScoreEquipe1() ?? 'N/A',
                $match->getEquipe2()->getNomEquipe(),
                $match->getScoreEquipe2() ?? 'N/A',
                $match->getStatutMatch()
            );
        }

        $response = new Response($csvContent);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="matches.csv"');

        return $response;
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

            if ($gameMatch->getStatutMatch() === 'completed') {
                $this->statistiquesEquipeUpdater->update($gameMatch);
            }

            return $this->redirectToRoute('app_game_match_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('game_match/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/upload-csv', name: 'app_game_match_upload_csv', methods: ['GET', 'POST'])]
    public function uploadCsv(Request $request, EntityManagerInterface $entityManager, EquipeRepository $equipeRepository): Response
    {
        if ($request->isMethod('POST')) {
            $file = $request->files->get('csv_file');

            if ($file && $file->isValid()) {
                $csvData = file_get_contents($file->getPathname());
                $rows = array_map('str_getcsv', explode("\n", $csvData));
                $header = array_shift($rows);

                foreach ($rows as $row) {
                    if (count($row) < 8) { // Ensure all required columns are present
                        continue; // Skip invalid rows
                    }

                    $data = array_combine($header, $row);

                    $gameMatch = new GameMatch();
                    $gameMatch->setDateMatch(new \DateTime($data['date_match']));
                    $gameMatch->setScoreEquipe1((int) $data['score_equipe1']);
                    $gameMatch->setScoreEquipe2((int) $data['score_equipe2']);
                    $gameMatch->setStatutMatch($data['statut_match']);

                    // Set related teams
                    $equipe1 = $equipeRepository->find($data['id_equipe1']);
                    $equipe2 = $equipeRepository->find($data['id_equipe2']);

                    if ($equipe1 && $equipe2) {
                        $gameMatch->setEquipe1($equipe1);
                        $gameMatch->setEquipe2($equipe2);
                    } else {
                        continue; // Skip if teams are invalid
                    }

                    // Set related tournament
                    $tournoi = $entityManager->getRepository(Tournoi::class)->find($data['id_tournoi']);
                    if ($tournoi) {
                        $gameMatch->setTournoi($tournoi);
                    } else {
                        continue; // Skip if the tournament is invalid
                    }

                    // Set related terrain
                    $terrain = $entityManager->getRepository(Terrain::class)->find($data['id_terrain']);
                    if ($terrain) {
                        $gameMatch->setTerrain($terrain);
                    } else {
                        continue; // Skip if the terrain is invalid
                    }

                    $entityManager->persist($gameMatch);
                }

                $entityManager->flush();

                $this->addFlash('success', 'CSV file uploaded successfully!');
                return $this->redirectToRoute('app_game_match_index');
            }

            $this->addFlash('error', 'Invalid file upload.');
        }

        return $this->render('game_match/upload_csv.html.twig');
    }

    #[Route('/user', name: 'app_game_match_user_index', methods: ['GET'])]
    public function userIndex(Request $request, GameMatchRepository $gameMatchRepository, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $gameMatchRepository->createQueryBuilder('gm')
            ->leftJoin('gm.equipe1', 'e1')
            ->leftJoin('gm.equipe2', 'e2')
            ->addSelect('e1', 'e2');

        if ($team = $request->query->get('team')) {
            $queryBuilder->andWhere('e1.nom_equipe LIKE :team OR e2.nom_equipe LIKE :team')
                ->setParameter('team', '%' . $team . '%');
        }

        if ($status = $request->query->get('status')) {
            $queryBuilder->andWhere('gm.statut_match = :status')
                ->setParameter('status', $status);
        }

        if ($date = $request->query->get('date')) {
            $queryBuilder->andWhere('gm.dateMatch = :date')
                ->setParameter('date', $date);
        }

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('game_match/index_user.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/user/{id}', name: 'app_game_match_user_show', methods: ['GET'])]
    public function userShow(GameMatch $gameMatch, EntityManagerInterface $entityManager): Response
    {
        $gameMatch = $entityManager->getRepository(GameMatch::class)
            ->createQueryBuilder('gm')
            ->leftJoin('gm.tournoi', 't')
            ->addSelect('t')
            ->where('gm.id = :id')
            ->setParameter('id', $gameMatch->getId())
            ->getQuery()
            ->getOneOrNullResult();

        if (!$gameMatch) {
            throw $this->createNotFoundException('Game match not found.');
        }

        $statRepo = $entityManager->getRepository(StatistiquesEquipe::class);
        $statsEquipe1 = $statRepo->findOneBy(['equipe' => $gameMatch->getEquipe1()]);
        $statsEquipe2 = $statRepo->findOneBy(['equipe' => $gameMatch->getEquipe2()]);

        $probabilities = $this->calculateProbabilities($statsEquipe1, $statsEquipe2);

        return $this->render('game_match/show_user.html.twig', [
            'game_match' => $gameMatch,
            'statsEquipe1' => $statsEquipe1,
            'statsEquipe2' => $statsEquipe2,
            'probabilities' => $probabilities,
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

            $this->statistiquesEquipeUpdater->update($gameMatch);

            return $this->redirectToRoute('app_game_match_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('game_match/edit.html.twig', [
            'game_match' => $gameMatch,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_game_match_delete', methods: ['POST'])]
    public function delete(Request $request, GameMatch $gameMatch, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $gameMatch->getId(), $request->request->get('_token'))) {
            $this->statistiquesEquipeUpdater->reverse($gameMatch);

            $entityManager->remove($gameMatch);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_game_match_index', [], Response::HTTP_SEE_OTHER);
    }

    private function calculateProbabilities(?StatistiquesEquipe $statsEquipe1, ?StatistiquesEquipe $statsEquipe2): array
    {
        if (!$statsEquipe1 || !$statsEquipe2) {
            return ['equipe1' => 'N/A', 'equipe2' => 'N/A'];
        }

        $offensiveStrengthEquipe1 = $statsEquipe1->getButsMarques() / max($statsEquipe1->getMatchsJoues(), 1);
        $defensiveStrengthEquipe1 = $statsEquipe1->getButsEncaisses() / max($statsEquipe1->getMatchsJoues(), 1);

        $offensiveStrengthEquipe2 = $statsEquipe2->getButsMarques() / max($statsEquipe2->getMatchsJoues(), 1);
        $defensiveStrengthEquipe2 = $statsEquipe2->getButsEncaisses() / max($statsEquipe2->getMatchsJoues(), 1);

        $strengthEquipe1 = $offensiveStrengthEquipe1 / max($defensiveStrengthEquipe2, 1);
        $strengthEquipe2 = $offensiveStrengthEquipe2 / max($defensiveStrengthEquipe1, 1);

        $totalStrength = $strengthEquipe1 + $strengthEquipe2;
        if ($totalStrength > 0) {
            $probEquipe1 = ($strengthEquipe1 / $totalStrength) * 100;
            $probEquipe2 = ($strengthEquipe2 / $totalStrength) * 100;
        } else {
            $probEquipe1 = $probEquipe2 = 50;
        }

        return [
            'equipe1' => round($probEquipe1, 2),
            'equipe2' => round($probEquipe2, 2),
        ];
    }
}