<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use App\Form\CarrierType;
use App\Repository\CarrierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/carrier')]
class CarrierCrudController extends AbstractController
{
    #[Route('/', name: 'admin_carrier_index', methods: ['GET'])]
    public function index(CarrierRepository $carrierRepository): Response
    {
        return $this->render('admin/carrier/index.html.twig', [
            'carriers' => $carrierRepository->findAll(),
        ]);
    }

    #[Route('/search', name: 'admin_carrier_search', methods: ['GET'])]
    public function search(Request $request, CarrierRepository $carrierRepository): JsonResponse
    {
        $searchTerm = $request->query->get('q', '');
        $carriers = $carrierRepository->findByName($searchTerm);

        $results = [];
        foreach ($carriers as $carrier) {
            $results[] = [
                'id' => $carrier->getId(),
                'text' => sprintf(
                    "%s - %s (%s)",
                    $carrier->getName(),
                    $carrier->getDescription(),
                    number_format(($carrier->getPrice() / 100), 2, ',', ' ') . ' TND'
                ),
                'url' => $this->generateUrl('admin_carrier_show', ['id' => $carrier->getId()])
            ];
        }

        return $this->json($results);
    }

    #[Route('/new', name: 'admin_carrier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $carrier = new Carrier();
        $form = $this->createForm(CarrierType::class, $carrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($carrier);
            $entityManager->flush();

            return $this->redirectToRoute('admin_carrier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/carrier/new.html.twig', [
            'carrier' => $carrier,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_carrier_show', methods: ['GET'])]
    public function show(Carrier $carrier): Response
    {
        return $this->render('admin/carrier/show.html.twig', [
            'carrier' => $carrier,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_carrier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Carrier $carrier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CarrierType::class, $carrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_carrier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/carrier/edit.html.twig', [
            'carrier' => $carrier,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_carrier_delete', methods: ['POST'])]
    public function delete(Request $request, Carrier $carrier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$carrier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($carrier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_carrier_index', [], Response::HTTP_SEE_OTHER);
    }
}