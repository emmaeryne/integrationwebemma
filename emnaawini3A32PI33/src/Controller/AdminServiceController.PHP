<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

#[Route('/adminservice')]
class AdminServiceController extends AbstractController
{
    private ServiceRepository $serviceRepository;
    private LoggerInterface $logger;

    public function __construct(ServiceRepository $serviceRepository, LoggerInterface $logger)
    {
        $this->serviceRepository = $serviceRepository;
        $this->logger = $logger;
    }

    #[Route('/services1', name: 'admin_services', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $search = $request->request->get('search', '');
        $services = $search ?
            $this->serviceRepository->findByNom($search) :
            $this->serviceRepository->findBy(['nombreReservations' => ['> 0']], ['nom' => 'ASC']);

        if (empty($services) && $search) {
            $this->addFlash('info', 'Aucun service trouvé pour la recherche.');
        } elseif (empty($services)) {
            $this->addFlash('info', 'Aucun service avec des réservations trouvé.');
        }

        return $this->render('/admin_service/index.html.twig', [
            'services' => $services,
            'search' => $search,
        ]);
    }
}