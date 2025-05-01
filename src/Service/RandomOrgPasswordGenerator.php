<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RandomOrgPasswordGenerator
{
    private $client;
    private $apiKey;

    public function __construct(
        HttpClientInterface $client,
        ParameterBagInterface $params
    ) {
        $this->client = $client;
        $this->apiKey = $params->get('random_org_api_key');
    }

    public function generatePassword(
        int $length = 16,
        bool $upper = true,
        bool $numbers = true,
        bool $symbols = true
    ): string {
        $chars = 'abcdefghijklmnopqrstuvwxyz';
        $chars .= $upper ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' : '';
        $chars .= $numbers ? '0123456789' : '';
        $chars .= $symbols ? '!@#$%^&*()_+-=' : '';

        $response = $this->client->request('POST', 'https://api.random.org/json-rpc/4/invoke', [
            'json' => [
                'jsonrpc' => '2.0',
                'method' => 'generateStrings',
                'params' => [
                    'apiKey' => $this->apiKey,
                    'n' => 1,
                    'length' => $length,
                    'characters' => $chars,
                    'replacement' => true
                ],
                'id' => 1
            ]
        ]);

        $data = $response->toArray();
        
        if (isset($data['result']['random']['data'][0])) {
            return $data['result']['random']['data'][0];
        }

        throw new \Exception('Erreur de génération : ' . ($data['error']['message'] ?? 'Inconnue'));
    }
}