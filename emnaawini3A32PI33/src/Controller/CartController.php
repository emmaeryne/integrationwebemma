<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mon-panier', name: 'app_panier')]
public function index(Cart $cart): Response
{
    $fullCarts = $cart->getfull();
    $stockError = false;
    
    foreach ($fullCarts as $item) {
        if ($item['quantity'] > $item['product']->getStockDispo()) {
            $stockError = true;
            break;
        }
    }

    return $this->render('cart/index.html.twig', [
        'fullCarts' => $fullCarts,
        'stockError' => $stockError,
        'total' => array_reduce($fullCarts, function($total, $item) {
            return $total + ($item['product']->getPrix() * $item['quantity']);
        }, 0)
    ]);
}

    #[Route('/cart/add/{id}', name: 'app_add_to_cart')]
    public function add(Cart $cart, $id): Response
    {
        $product = $this->entityManager->getRepository(Produit::class)->find($id);
        
        if (!$product || $product->getStockDispo() <= 0) {
            $this->addFlash('error', 'Ce produit n\'est plus disponible');
            return $this->redirectToRoute('app_panier');
        }

        if (!$cart->add($id)) {
            $this->addFlash('error', sprintf("Impossible d'ajouter plus de %s - stock insuffisant", $product->getNomProduit()));
        }
        
        return $this->redirectToRoute('app_panier');
    }

    #[Route('/cart/decrease/{id}', name: 'app_decrease_from_cart')]
    public function decrease(Cart $cart, $id): Response
    {
        $cart->decrease($id);
        return $this->redirectToRoute('app_panier');
    }

    #[Route('/cart/remove', name: 'app_remove_cart')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();
        return $this->redirectToRoute('app_products');
    }

    #[Route('/cart/delete/{id}', name: 'app_delete_from_cart')]
    public function delete(Cart $cart, $id): Response
    {
        $cart->delete($id);
        return $this->redirectToRoute('app_panier');
    }

    #[Route('/order', name: 'app_order')]
    public function order(Cart $cart): Response
    {
        $fullCarts = $cart->getfull();
        
        foreach ($fullCarts as $item) {
            if ($item['product']->getStockDispo() < $item['quantity']) {
                $this->addFlash('error', sprintf(
                    "Commande impossible : quantité de %s (%d) > stock disponible (%d)",
                    $item['product']->getNomProduit(),
                    $item['quantity'],
                    $item['product']->getStockDispo()
                ));
                return $this->redirectToRoute('app_panier');
            }
        }
        
        return $this->render('order/index.html.twig', [
            'fullCarts' => $fullCarts,
            'total' => array_reduce($fullCarts, 
                fn($total, $item) => $total + ($item['product']->getPrix() * $item['quantity']), 
                0)
        ]);
    }
}