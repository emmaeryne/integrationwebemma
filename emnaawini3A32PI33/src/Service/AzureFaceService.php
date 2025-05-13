<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class AzureFaceService
{
    private HttpClientInterface $httpClient;
    private LoggerInterface $logger;
    private string $apiKey;
    private string $endpoint;

    public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger, string $apiKey, string $endpoint)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->apiKey = $apiKey;
        $this->endpoint = rtrim($endpoint, '/') . '/face/v1.0/detect';
    }

    public function detectFace(string $imageUrl): bool
    {
        try {
            $response = $this->httpClient->request('POST', $this->endpoint, [
                'headers' => [
                    'Ocp-Apim-Subscription-Key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'url' => $imageUrl,
                ],
                'query' => [
                    'returnFaceId' => 'true',
                    'returnFaceLandmarks' => 'false',
                ],
            ]);

            $data = $response->toArray();

            $this->logger->info('Azure Face API Response', ['response' => $data]);

            return !empty($data);
        } catch (\Exception $e) {
            $this->logger->error('Azure Face API Error', [
                'error' => $e->getMessage(),
                'imageUrl' => $imageUrl
            ]);
            return false;
        }
    }
}
