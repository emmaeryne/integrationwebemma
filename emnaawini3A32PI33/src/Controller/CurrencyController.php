<?php
// src/Controller/CurrencyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CurrencyController extends AbstractController
{
    #[Route('/api/convert-currency', name: 'api_convert_currency', methods: ['POST'])]
public function convert(Request $request): JsonResponse
{
    $data = json_decode($request->getContent(), true);
    $amount = $data['amount'] ?? null;
    $from = $data['from'] ?? null;
    $to = $data['to'] ?? null;

    if (!$amount || !$from || !$to) {
        return $this->json(['error' => 'Données incomplètes'], 400);
    }

    $apiKey = '26f646280530cb3a70059bc8';
    $url = "https://v6.exchangerate-api.com/v6/{$apiKey}/latest/{$from}";

    try {
        $response = file_get_contents($url);
        $result = json_decode($response, true);

        if (!isset($result['conversion_rates'][$to])) {
            return $this->json(['error' => 'Devise cible invalide'], 400);
        }

        $rate = $result['conversion_rates'][$to];
        $converted = $amount * $rate;

        return $this->json([
            'converted_amount' => round($converted, 2),
            'rate' => $rate
        ]);
    } catch (\Exception $e) {
        return $this->json(['error' => 'Erreur API : ' . $e->getMessage()], 500);
    }
}
}