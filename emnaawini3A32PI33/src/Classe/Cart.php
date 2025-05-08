<?php
namespace App\Classe;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraints\Length;

class Cart{
    private $session;
    private $entityManager;
    private $requestStack;
    public function __construct(RequestStack $requestStack,EntityManagerInterface $entityManager){
        $this->requestStack=$requestStack;
        $this->entityManager=$entityManager;
    }

    public function add($id)
{
    $cart = $this->requestStack->getSession()->get('cart', []);
    $product = $this->entityManager->getRepository(Produit::class)->find($id);

    if ($product) {
        $currentQuantity = isset($cart[$id]) ? $cart[$id] : 0;
        if ($currentQuantity + 1 > $product->getStockDispo()) {
            // Ne pas ajouter si le stock est dépassé
            return false;
        }
        $cart[$id] = $currentQuantity + 1;
    }

    $this->requestStack->getSession()->set('cart', $cart);
    return true;
}

    public function decrease($id)
    {
        $cart=$this->requestStack->getSession()->get('cart',[]);
        if($cart[$id]>1){
            $cart[$id]--;
        }else{
            unset($cart[$id]);
        }
        $this->requestStack->getSession()->set('cart',$cart);
    }

    public function get()
    {
        return $this->requestStack->getSession()->get('cart');
    }
    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }
    public function delete($id)
    {
        $cart=$this->requestStack->getSession()->get('cart',[]);
        unset($cart[$id]);
        $this->requestStack->getSession()->set('cart',$cart);
    }

    public function getfull()
{
    $fullCarts = [];
    if ($this->get()) {
        foreach ($this->get() as $id => $quantity) {
            $product_object = $this->entityManager->getRepository(Produit::class)->findOneById($id);
            if (!$product_object) {
                $this->delete($id);
                continue;
            }
            
            // Si la quantité demandée dépasse le stock, ajuster à la quantité maximale disponible
            if ($quantity > $product_object->getStockDispo()) {
                $quantity = $product_object->getStockDispo();
                $this->requestStack->getSession()->set('cart', array_merge(
                    $this->get(),
                    [$id => $quantity]
                ));
            }
            
            $fullCarts[] = [
                'product' => $product_object,
                'quantity' => $quantity
            ];
        }
    }
    return $fullCarts;
}
}