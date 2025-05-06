<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Produit;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/avis')]
final class AvisController extends AbstractController
{
    #[Route('/produit/{id}', name: 'app_avis_by_produit', methods: ['GET'], priority: 10)]
    public function getAvisByProduit(Produit $produit, AvisRepository $avisRepository): JsonResponse
    {
        $avis = $avisRepository->findBy(['produit' => $produit]);

        $avisData = [];
        foreach ($avis as $a) {
            $avisData[] = [
                'id' => $a->getId(), // 🔥 NÉCESSAIRE pour delete/edit JS
                'auteur' => $a->getAuteur(),
                'commentaire' => $a->getCommentaire(),
                'note' => $a->getNote()
            ];
        }

        return $this->json($avisData);
    }

    #[Route('/new', name: 'app_avis_add', methods: ['POST'])]
    public function addAvis(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);//tableau PHP.

        if (!isset($data['auteur'], $data['commentaire'], $data['note'], $data['produitId'])) {
            return new JsonResponse(['error' => 'Données incomplètes'], Response::HTTP_BAD_REQUEST);
        }

        $produit = $em->getRepository(Produit::class)->find($data['produitId']);
        if (!$produit) {
            return new JsonResponse(['error' => 'Produit introuvable'], Response::HTTP_NOT_FOUND);
        }

        $avis = new Avis();
        $avis->setAuteur($data['auteur']);
        $avis->setCommentaire($data['commentaire']);
        $avis->setNote((int)$data['note']);
        $avis->setProduit($produit);
        $avis->setCreatedAt(new \DateTime());

        $em->persist($avis);//prépare Doctrine à sauvegarder l’objet
        $em->flush();//exécute la requête SQL (INSERT)

        return new JsonResponse(['success' => true]);
    }

    #[Route('/{id}', name: 'app_avis_show', methods: ['GET'])]
    public function show(Avis $avi): Response
    {
        return $this->render('avis/show.html.twig', [
            'avi' => $avi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_avis_edit_ajax', methods: ['PUT'])]
    public function editAvis(Request $request, Avis $avis, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['auteur'], $data['commentaire'], $data['note'])) {
            return new JsonResponse(['error' => 'Champs requis manquants'], Response::HTTP_BAD_REQUEST);
        }

        $avis->setAuteur($data['auteur']);
        $avis->setCommentaire($data['commentaire']);
        $avis->setNote((int)$data['note']);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    // ✅ SUPPRESSION - corriger la route ici
    #[Route('/{id}', name: 'app_avis_delete_ajax', methods: ['DELETE'])]
    public function deleteAvis(Avis $avis, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($avis);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }
}
