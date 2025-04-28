<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class StatsController extends AbstractController
{
    #[Route('/admin/stats', name: 'admin_stats')]
    public function index(ProduitRepository $produitRepo, ChartBuilderInterface $chartBuilder): Response
    {
        $produits = $produitRepo->findAll();

        $labels = [];
        $stock = [];
        $fournisseurs = [];
        $prixUnder = 0;
        $prixOver = 0;
        $dates = [];

        foreach ($produits as $produit) {
            // 📦 Stock par produit
            $labels[] = $produit->getNomProduit();
            $stock[] = $produit->getStockDispo();

            // 🧑‍💼 Par fournisseur
            $f = $produit->getFournisseur();
            $fournisseurs[$f] = ($fournisseurs[$f] ?? 0) + 1;

            // 💶 Répartition prix
            if ($produit->getPrix() < 50) {
                $prixUnder++;
            } else {
                $prixOver++;
            }

            // 📅 Produits par date
            $d = $produit->getDate()?->format('Y-m-d') ?? 'Non daté';
            $dates[$d] = ($dates[$d] ?? 0) + 1;
        }

        // 🟢 On renvoie des tableaux simples prêts pour JSON dans Twig
        return $this->render('back/produit/stat.html.twig', [
            'labels' => $labels,
            'stock' => $stock,
            'fournisseurLabels' => array_keys($fournisseurs),
            'fournisseurValues' => array_values($fournisseurs),
            'prixUnder' => $prixUnder,
            'prixOver' => $prixOver,
            'dateLabels' => array_keys($dates),
            'dateValues' => array_values($dates),
        ]);
    }
}
