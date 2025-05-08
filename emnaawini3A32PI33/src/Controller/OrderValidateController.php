<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderValidateController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande/merci/{stripeSessionId}', name: 'app_order_validate')]
    public function index(Cart $cart, string $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        /** @var Users|null $currentUser */
        $currentUser = $this->getUser();

        if (!$order || $order->getUser() !== $currentUser) {
            return $this->redirectToRoute('app_home');
        }

        if (!$order->isIsPaid()) {
            // Empty cart session
            $cart->remove();

            // Set order as paid
            $order->setIsPaid(true);
            $this->entityManager->flush();
        }

        return $this->render('order_validate/index.html.twig', [
            'order' => $order,
        ]);
    }
}
