<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class HuggingFaceChatbot
{
    private const MAX_RETRIES = 2;
    private const RETRY_DELAY = 1;

    public function __construct(
        private HttpClientInterface $client,
        private string $apiKey,
        private string $model,
        private int $timeout
    ) {}

    public function getResponse(string $message): string
    {
        $retryCount = 0;
        
        do {
            try {
                $response = $this->makeRequest($message);
                $data = $response->toArray();
                
                if (isset($data[0]['generated_text'])) {
                    return $this->cleanResponse($data[0]['generated_text']);
                }
                
            } catch (TransportExceptionInterface $e) {
                error_log('HTTP Error: '.$e->getMessage());
            } catch (\Exception $e) {
                error_log('API Error: '.$e->getMessage());
            }
            
            $retryCount++;
            if ($retryCount < self::MAX_RETRIES) {
                sleep(self::RETRY_DELAY);
            }
        } while ($retryCount < self::MAX_RETRIES);
        
        return $this->getFallbackResponse($message);
    }

    private function makeRequest(string $message)
    {
        return $this->client->request(
            'POST',
            "https://api-inference.huggingface.co/models/{$this->model}",
            [
                'headers' => [
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'inputs' => $this->formatInput($message),
                    'parameters' => [
                        'max_new_tokens' => 150,
                        'temperature' => 0.9,
                        'top_p' => 0.95,
                        'repetition_penalty' => 1.2,
                        'do_sample' => true,
                    ],
                    'options' => [
                        'wait_for_model' => true
                    ]
                ],
                'timeout' => $this->timeout
            ]
        );
    }

    private function formatInput(string $message): string
    {
        return "<|user|>" . trim($message) . "<|assistant|>";
    }

    private function cleanResponse(string $text): string
    {
        $text = preg_replace('/<\|.*?\|>/', '', $text);
        $text = preg_replace('/[^[:print:]]/', '', $text);
        return trim($text);
    }

    private function getFallbackResponse(string $message): string
    {
        $message = strtolower(trim($message));
        
        if (str_contains($message, 'bonjour')) {
            return 'Bonjour ! Je rencontre un problème technique.';
        }
        
        return 'Désolé, je ne peux pas répondre pour le moment.';
    }

    public function getModel(): string
    {
        return $this->model;
    }
}