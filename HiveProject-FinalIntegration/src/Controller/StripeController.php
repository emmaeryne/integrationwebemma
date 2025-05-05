<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\JsonResponse;

class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(): Response
    {
        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
        ]);
    }


    #[Route('/stripe/create-checkout-session/{reference}', name: 'app_stripe_ceckout_session', methods: ['POST'])]
public function createCharge(Request $request, Cart $cart, $reference, EntityManagerInterface $entityManager)
{
    Stripe::setApiKey('sk_test_51RIGcOBCMRB1p7HzGp7RVgFdp4i4mfU5JHAal7hLIEwtf5zZC8di5WhN6cuduU1OFsZxKzAcjf4RAZCCYJFQsdHY009FGmM6Xy');
    $YOUR_DOMAIN = 'https://127.0.0.1:8000';
    $products_for_stripe = []; // Fixed variable name (was product_for_stripe)

    $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

    if (!$order) {
       return $this->redirectToRoute('app_order');
    }

    foreach ($order->getOrderDetails()->getValues() as $product) {
        $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
        $products_for_stripe[] = [
            'price_data' => [
                'currency' => 'USD',
                'unit_amount' => (int) round($product->getPrice() * 100), // Convert float to cents
                'product_data' => [
                    'name' => $product->getProduct(),
                    // 'images' => [$YOUR_DOMAIN."/uploads/images/".$product_object->getImage()],
                ],
            ],
            'quantity' => $product->getQuantity(),
        ];
    }

    $products_for_stripe[] = [
        'price_data' => [
            'currency' => 'USD',
            'unit_amount' => (int) round($order->getCarrierPrice() * 100), // Convert float to cents
            'product_data' => [
                'name' => $order->getCarrierName(),
                'images' => [$YOUR_DOMAIN],
            ],
        ],
        'quantity' => 1,
    ];

    // Fixed the line_items structure - removed the extra array wrapping
    $checkout_session = \Stripe\Checkout\Session::create([
        'customer_email' => $this->getUser()->getEmail(),
        'line_items' => $products_for_stripe, // Directly use the array
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
        'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
    ]);

    $order->setStripeSessionId($checkout_session->id);
    $entityManager->flush();

    return $this->redirect($checkout_session->url, 303);
}
}