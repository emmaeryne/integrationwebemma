<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FournisseurController extends AbstractController
{
    #[Route('/api/fournisseurs', name: 'api_fournisseurs', methods: ['POST'])]
    public function getFournisseurs(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $categorie = $data['categorie'] ?? '';
        $produit = $data['produit'] ?? '';

        if (empty($categorie) || empty($produit)) {
            return $this->json(['error' => 'Champs requis manquants'], 400);
        }

        $apiKey = "AIzaSyAppRebP1i5iNeX_hbZk6DZ465NatLYDZE";
        $searchEngineId = "1256d1da04f58449a";
        $query = urlencode("Fournisseur $produit $categorie Tunisie site:.tn");
        $url = "https://www.googleapis.com/customsearch/v1?q=$query&key=$apiKey&cx=$searchEngineId";

        try {
            // Utiliser un contexte avec timeout pour éviter le blocage
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5 // secondes
                ]
            ]);

            $response = @file_get_contents($url, false, $context);

            if ($response === false) {
                return $this->json(['error' => 'Erreur lors de la requête à l’API Google'], 500);
            }

            $results = json_decode($response, true);
            $fournisseurs = [];

            if (isset($results['items']) && is_array($results['items'])) {
                foreach (array_slice($results['items'], 0, 5) as $item) {
                    $title = $item['title'] ?? 'Fournisseur inconnu';
                    $link = $item['link'] ?? '';

                    // Ignorer les documents non web
                    if (!preg_match('/\.(pdf|doc|docx|ppt|pptx)$/i', $link)) {
                        $fournisseurs[] = $title;
                    }
                }
            }

            return $this->json(['fournisseurs' => $fournisseurs]);
        } catch (\Throwable $e) {
            return $this->json(['error' => 'Exception : ' . $e->getMessage()], 500);
        }
    }
}
