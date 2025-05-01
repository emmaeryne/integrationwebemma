<?php

namespace App\Controller;

use App\Repository\CategorieProduitRepository;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriePdfController extends AbstractController
{
    #[Route('/admin/categorie/pdf', name: 'categorie_pdf')]
public function generatePdf(CategorieProduitRepository $repo, Pdf $knpSnappyPdf): Response
{
    $categories = $repo->findAll();

    $html = $this->renderView('categorie_produit/pdf.html.twig', [
        'categories' => $categories
    ]);

    return new Response(
        $knpSnappyPdf->getOutputFromHtml($html),
        200,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="Liste des Catégories.pdf"', // ✅ garder attachment ici
        ]
    );
}
}
