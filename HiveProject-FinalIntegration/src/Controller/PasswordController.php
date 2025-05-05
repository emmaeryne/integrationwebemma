<?php
namespace App\Controller;

use App\Service\RandomOrgPasswordGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PasswordController extends AbstractController
{
    #[Route('/api/password/generate', name: 'generate_password')]
    public function generate(RandomOrgPasswordGenerator $generator): JsonResponse
    {
        try {
            $password = $generator->generatePassword(16, true, true, true);
            return $this->json([
                'password' => $password,
                'strength' => 'very strong'
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}