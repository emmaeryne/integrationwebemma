<?php
namespace App\Service;

use App\Entity\Equipe;
use App\Entity\StatistiquesEquipe;
use App\Repository\EquipeRepository;
use Doctrine\ORM\EntityManagerInterface;

class StatistiquesEquipeInitializer
{
    private EquipeRepository $equipeRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(EquipeRepository $equipeRepository, EntityManagerInterface $entityManager)
    {
        $this->equipeRepository = $equipeRepository;
        $this->entityManager = $entityManager;
    }

    public function initialize(): void
    {
        $equipes = $this->equipeRepository->findAll();

        foreach ($equipes as $equipe) {
            $existingStats = $this->entityManager->getRepository(StatistiquesEquipe::class)->findOneBy(['equipe' => $equipe]);

            if (!$existingStats) {
                $stats = new StatistiquesEquipe();
                $stats->setEquipe($equipe);
                $this->entityManager->persist($stats);
            }
        }

        $this->entityManager->flush();
    }
}