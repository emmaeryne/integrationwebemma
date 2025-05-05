<?php
// src/Controller/ChatbotController.php
namespace App\Controller;

use App\Service\HuggingFaceChatbot;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatbotController extends AbstractController
{
    #[Route('/api/chat', name: 'app_chat', methods: ['POST'])]
    public function chat(Request $request, HuggingFaceChatbot $chatbot): JsonResponse
    {
        // Vérification du Content-Type
        if (!$request->headers->has('Content-Type') || 
            !str_contains($request->headers->get('Content-Type'), 'application/json')) {
            return new JsonResponse(
                ['error' => 'Content-Type must be application/json'],
                Response::HTTP_UNSUPPORTED_MEDIA_TYPE
            );
        }

        // Décodage et validation du JSON
        $data = json_decode($request->getContent(), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(
                ['error' => 'Invalid JSON format'],
                Response::HTTP_BAD_REQUEST
            );
        }

        if (!isset($data['message']) || !is_string($data['message']) || empty(trim($data['message']))) {
            return new JsonResponse(
                ['error' => 'Message must be a non-empty string'],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $response = $chatbot->getResponse(trim($data['message']));
            
            // Nettoyage supplémentaire de la réponse
            $cleanResponse = preg_replace('/[^[:print:]]/', '', $response);
            $cleanResponse = trim($cleanResponse);
            
            return new JsonResponse([
                'response' => $cleanResponse,
                'status' => 'success',
                'model' => $chatbot->getModel() // Ajoutez cette méthode à votre service si besoin
            ]);
            
        } catch (\Exception $e) {
            // Log l'erreur pour le débogage
            error_log('Chatbot error: ' . $e->getMessage());
            
            return new JsonResponse(
                ['error' => 'Chatbot service is temporarily unavailable'],
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }
    }

    #[Route('/api/chat/test', name: 'app_chat_test', methods: ['GET'])]
    public function testConnection(HuggingFaceChatbot $chatbot): JsonResponse
    {
        try {
            $testMessage = "Bonjour, peux-tu confirmer que tu fonctionnes ?";
            $response = $chatbot->getResponse($testMessage);
            
            return new JsonResponse([
                'status' => 'success',
                'response' => $response,
                'test' => true
            ]);
            
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => 'Service unavailable: ' . $e->getMessage()],
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }
    }
}