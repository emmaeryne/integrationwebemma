<?php

namespace App\Controller;

use App\Entity\Terrain;
use App\Form\TerrainType;
use App\Repository\TerrainRepository;
use App\Repository\TournoiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/terrain')]
final class TerrainController extends AbstractController
{
    #[Route('/', name: 'app_terrain_index', methods: ['GET'])]
    public function index(TerrainRepository $terrainRepository): Response
    {
        $terrains = $terrainRepository->findAll();

        return $this->render('terrain/index.html.twig', [
            'terrains' => $terrains,
        ]);
    }

    #[Route('/new', name: 'app_terrain_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('create_terrain', $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token.');
            return $this->redirectToRoute('app_terrain_index');
        }

        $terrain = new Terrain();
        $entityManager->persist($terrain);
        $entityManager->flush();

        $this->addFlash('success', 'Terrain created successfully!');

        return $this->redirectToRoute('app_terrain_index');
    }

    #[Route('/{id_terrain}', name: 'app_terrain_show', methods: ['GET'])]
    public function show(int $id_terrain, TerrainRepository $terrainRepository): Response
    {
        $terrain = $terrainRepository->find($id_terrain);

        if (!$terrain) {
            throw $this->createNotFoundException('Terrain not found.');
        }

        $matches = $terrain->getMatches(); 
        return $this->render('terrain/show.html.twig', [
            'terrain' => $terrain,
            'matches' => $matches,
        ]);
    }

    #[Route('/{id_terrain}/edit', name: 'app_terrain_edit', methods: ['GET', 'POST'])]
    public function edit(int $id_terrain): Response
    {
    }

    #[Route('/{id_terrain}', name: 'app_terrain_delete', methods: ['POST'])]
    public function delete(Request $request, TerrainRepository $terrainRepository, EntityManagerInterface $entityManager, int $id_terrain): Response
    {
        $terrain = $terrainRepository->find($id_terrain);

        if (!$terrain) {
            $this->addFlash('error', 'Terrain not found.');
            return $this->redirectToRoute('app_terrain_index');
        }

        if ($this->isCsrfTokenValid('delete' . $terrain->getIdTerrain(), $request->request->get('_token'))) {
            $entityManager->remove($terrain);
            $entityManager->flush();

            $this->addFlash('success', 'Terrain deleted successfully.');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_terrain_index');
    }
}
