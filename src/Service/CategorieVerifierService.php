<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CategorieVerifierService
{
    private $client;
    private $apiUrl;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->apiUrl = 'https://127.0.0.1:5004/predict';
    }

    public function verifierCorrespondance(string $nomProduit, string $categorieReelle): array
    {
        try {
            $response = $this->client->request('POST', $this->apiUrl, [
                'json' => [
                    'nom_produit' => $this->normalize($nomProduit),
                    'categorie_reelle' => $this->normalize($categorieReelle),
                ],
                'verify_peer' => false,
                'verify_host' => false,
            ]);

            return $response->toArray();

        } catch (\Exception $e) {
            return [
                'coherent' => null,
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    private function normalize(string $text): string
    {
        $text = mb_strtolower(trim($text), 'UTF-8');
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = preg_replace('/[^a-z0-9 ]/', '', $text); // garde que lettres/chiffres/espaces
        return $text;
    }
}