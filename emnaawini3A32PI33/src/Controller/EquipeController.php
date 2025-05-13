<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\StatistiquesEquipe;
use App\Entity\GameMatch;
use App\Form\EquipeType;
use App\Repository\EquipeRepository;
use App\Repository\JoueurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/equipe')]
final class EquipeController extends AbstractController
{
    #[Route(name: 'app_equipe_index', methods: ['GET'])]
    public function index(Request $request, EquipeRepository $equipeRepository, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $equipeRepository->createQueryBuilder('e');

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('equipe/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_equipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $equipe = new Equipe();
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($equipe);

            $statistiques = new StatistiquesEquipe();
            $statistiques->setEquipe($equipe);
            $entityManager->persist($statistiques);

            $entityManager->flush();

            return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('equipe/new.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
        ]);
    }

    #[Route('/upload-csv', name: 'app_equipe_upload_csv', methods: ['GET', 'POST'])]
    public function uploadCsv(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $file = $request->files->get('csv_file');

            if ($file && $file->isValid() && $file->getClientOriginalExtension() === 'csv') {
                $handle = fopen($file->getPathname(), 'r');
                $header = fgetcsv($handle);

                while (($row = fgetcsv($handle)) !== false) {
                    $data = array_combine($header, $row);

                    $equipe = new Equipe();
                    $equipe->setNomEquipe($data['nom_equipe']);
                    $equipe->setTypeEquipe($data['type_equipe']);

                    $entityManager->persist($equipe);
                }

                fclose($handle);
                $entityManager->flush();

                $this->addFlash('success', 'Teams imported successfully!');
                return $this->redirectToRoute('app_equipe_index');
            }

            $this->addFlash('error', 'Invalid file. Please upload a valid CSV file.');
        }

        return $this->render('equipe/upload_csv.html.twig');
    }

    #[Route('/{id_equipe}', name: 'app_equipe_show', methods: ['GET'])]
    public function show(Equipe $equipe, EntityManagerInterface $entityManager): Response
    {
        $statistiques = $entityManager->getRepository(StatistiquesEquipe::class)->findOneBy(['equipe' => $equipe]);

        return $this->render('equipe/show.html.twig', [
            'equipe' => $equipe,
            'joueurs' => $equipe->getJoueurs(),
            'statistiques' => $statistiques,
        ]);
    }

    #[Route('/{id_equipe}/edit', name: 'app_equipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Equipe $equipe, EntityManagerInterface $entityManager, JoueurRepository $joueurRepository): Response
    {
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
        }

        $availableJoueurs = $joueurRepository->findBy(['equipe' => null]);

        return $this->render('equipe/edit.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
            'joueurs' => $equipe->getJoueurs(),
            'available_joueurs' => $availableJoueurs, 
        ]);
    }

    #[Route('/{id_equipe}', name: 'app_equipe_delete', methods: ['POST'])]
    public function delete(Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $equipe->getIdEquipe(), $request->request->get('_token'))) {
            // Remove related matches
            $matches = $entityManager->getRepository(GameMatch::class)->findBy(['equipe1' => $equipe]);
            foreach ($matches as $match) {
                $entityManager->remove($match);
            }

            // Remove the team
            $entityManager->remove($equipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id_equipe}/remove-joueur/{id_joueur}', name: 'app_equipe_remove_joueur', methods: ['POST'])]
    public function removeJoueur(int $id_equipe, int $id_joueur, EntityManagerInterface $entityManager, JoueurRepository $joueurRepository, EquipeRepository $equipeRepository, Request $request): Response
    {
        $equipe = $equipeRepository->find($id_equipe);
        $joueur = $joueurRepository->find($id_joueur);

        if (!$equipe || !$joueur) {
            $this->addFlash('error', 'Equipe or Joueur not found.');
            return $this->redirectToRoute('app_equipe_edit', ['id_equipe' => $id_equipe]);
        }

        if ($this->isCsrfTokenValid('remove_joueur' . $id_joueur, $request->request->get('_token'))) {
            $joueur->setEquipe(null);
            $entityManager->flush();

            $this->addFlash('success', 'Joueur removed from the team successfully.');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_equipe_edit', ['id_equipe' => $id_equipe]);
    }
    #[Route('/{id_equipe}/add-joueur', name: 'app_equipe_add_joueur', methods: ['POST'])]
    public function addJoueur(int $id_equipe, Request $request, EntityManagerInterface $entityManager, JoueurRepository $joueurRepository, EquipeRepository $equipeRepository): Response
    {
        $equipe = $equipeRepository->find($id_equipe);
        $id_joueur = $request->request->get('id_joueur');
        $joueur = $joueurRepository->find($id_joueur);

        if (!$equipe || !$joueur) {
            $this->addFlash('error', 'Equipe or Joueur not found.');
            return $this->redirectToRoute('app_equipe_edit', ['id_equipe' => $id_equipe]);
        }

        $joueur->setEquipe($equipe); // Assign the player to the team
        $entityManager->flush();

        $this->addFlash('success', 'Player added to the team successfully.');
        return $this->redirectToRoute('app_equipe_edit', ['id_equipe' => $id_equipe]);
    }

    #[Route('/{id_equipe}/upload-stats', name: 'app_equipe_upload_stats', methods: ['GET', 'POST'])]
    public function uploadStats(Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $file = $request->files->get('csv_file');

            if ($file && $file->isValid() && $file->getClientOriginalExtension() === 'csv') {
                $handle = fopen($file->getPathname(), 'r');
                $header = fgetcsv($handle);

                while (($row = fgetcsv($handle)) !== false) {
                    $data = array_combine($header, $row);

                    // Find or create the statistics for the team
                    $statistiques = $entityManager->getRepository(StatistiquesEquipe::class)->findOneBy(['equipe' => $equipe]);
                    if (!$statistiques) {
                        $statistiques = new StatistiquesEquipe();
                        $statistiques->setEquipe($equipe);
                        $entityManager->persist($statistiques);
                    }

                    // Update the statistics
                    $statistiques->setMatchsJoues((int) $data['matchs_joues']);
                    $statistiques->setVictoires((int) $data['victoires']);
                    $statistiques->setDefaites((int) $data['defaites']);
                    $statistiques->setNuls((int) $data['nuls']);
                    $statistiques->setButsMarques((int) $data['buts_marques']);
                    $statistiques->setButsEncaisses((int) $data['buts_encaisses']);
                }

                fclose($handle);
                $entityManager->flush();

                $this->addFlash('success', 'Team statistics updated successfully!');
                return $this->redirectToRoute('app_equipe_index');
            }

            $this->addFlash('error', 'Invalid file. Please upload a valid CSV file.');
        }

        return $this->render('equipe/upload_stats.html.twig', [
            'equipe' => $equipe,
        ]);
    }
}
