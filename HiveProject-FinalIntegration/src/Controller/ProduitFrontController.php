<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Produit;
use App\Repository\AvisRepository;
use Symfony\Component\HttpFoundation\Request;

class ProduitFrontController extends AbstractController
{
    #[Route('/produits', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('front/produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }
    
}
