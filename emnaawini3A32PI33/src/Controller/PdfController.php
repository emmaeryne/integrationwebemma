<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\KernelInterface;

class PdfController extends AbstractController
{
    #[Route('/pdf', name: 'pdf')]
    public function generatePdf(
        Pdf $knpSnappyPdf,
        ProduitRepository $produitRepository,
        KernelInterface $kernel
    ): Response {
        $produits = $produitRepository->findAll();

        // Chemin absolu vers le logo
        $logoPath = realpath($kernel->getProjectDir() . '/public/images/logo.png');

        if ($logoPath === false) {
            throw new \Exception('Le fichier logo n\'existe pas.');
        }

        // Formater le chemin pour wkhtmltopdf
        $logoPath = 'file://' . str_replace('\\', '/', $logoPath);

        // Rendu du HTML avec Twig
        $html = $this->renderView('back/produit/produits_pdf.html.twig', [
            'produits' => $produits,
            'logoPath' => $logoPath
        ]);

        // Activation de l'accès aux fichiers locaux
        $knpSnappyPdf->setOption('enable-local-file-access', true);

        return new Response(
            $knpSnappyPdf->getOutputFromHtml($html),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="produits.pdf"',
            ]
        );
    }
}