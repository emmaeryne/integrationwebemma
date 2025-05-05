<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\Produit1Type;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Label\Font\Font;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Margin\Margin;

#[Route('/admin/dashboard')]
final class ProduitController extends AbstractController
{
    #[Route('/produits', name: 'app2_produit_index')]
    public function index(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();
        $qrCodes = [];

        foreach ($produits as $produit) {
            $qrData = sprintf(
                "Nom : %s\nPrix : %.2f €\nStock : %d",
                $produit->getNomProduit(),
                $produit->getPrix(),
                $produit->getStockDispo()
            );

            // Construction manuelle avec Builder
            $builder = new Builder(
                new PngWriter(),
                [], // writerOptions
                false, // validateResult
                $qrData, // data
                new Encoding('UTF-8'),
                ErrorCorrectionLevel::High,
                300, // size
                10, // margin
                RoundBlockSizeMode::Margin,
                new Color(0, 0, 0), // foregroundColor
                new Color(255, 255, 255), // backgroundColor
                'Infos Produit', // labelText
                new Font('C:\HiveProject-FinalIntegration\public\fonts\open_sans.ttf', 12), // Chemin corrigé
                LabelAlignment::Center,
                new Margin(0, 10, 10, 10), // labelMargin
                new Color(0, 0, 0), // labelTextColor
                '', // logoPath
                null, // logoResizeToWidth
                null, // logoResizeToHeight
                false // logoPunchoutBackground
            );

            $result = $builder->build();
            $qrCodes[$produit->getId()] = $result->getDataUri();
        }

        return $this->render('back/produit/index.html.twig', [
            'produits' => $produits,
            'qrCodes' => $qrCodes,
        ]);
    }

    #[Route('/new', name: 'app2_produit_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $em): Response
{
    $produit = new Produit();

    $fournisseurChoisi = $request->request->all()['produit1']['Fournisseur'] ?? null;

    $form = $this->createForm(Produit1Type::class, $produit, [
        'fournisseurs' => $fournisseurChoisi ? [$fournisseurChoisi => $fournisseurChoisi] : [],
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($produit);
        $em->flush();

        return $this->redirectToRoute('app2_produit_index');
    }

    return $this->render('back/produit/new.html.twig', [
        'form' => $form,
        'produit' => $produit,
    ]);
}

    #[Route('/{id}', name: 'app2_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('back/produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app2_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $produit->setDate(new \DateTime());
        $form = $this->createForm(Produit1Type::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app2_produit_index');
        }

        return $this->render('back/produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app2_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app2_produit_index');
    }
}