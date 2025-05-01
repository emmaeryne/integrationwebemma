<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\Users;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande', name: 'app_order')]
    public function order(Cart $cart): Response
    {
        $fullCarts = $cart->getFull();
        
        // Stock availability check
        foreach ($fullCarts as $item) {
            if ($item['quantity'] > $item['product']->getStockDispo()) {
                $this->addFlash('error', 'La quantité demandée pour le produit "' . $item['product']->getNomProduit() . '" dépasse le stock disponible.');
                return $this->redirectToRoute('app_panier');
            }
        }

        /** @var Users $user */
        $user = $this->getUser();
        
        $form = $this->createForm(OrderType::class, null, [
            'user' => $user
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cartProducts' => $fullCarts
        ]);
    }
    #[Route('/commande/recap', name: 'app_order_recap', methods: ['POST'])]
    public function add(Cart $cart, Request $request): Response
    {
        /** @var Users $user */
        $user = $this->getUser();
    
        $form = $this->createForm(OrderType::class, null, [
            'user' => $user
        ]);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTimeImmutable('now');
            $carrier = $form->get('carriers')->getData();
            $delivery = $form->get('addresses')->getData();
    
            if (!$delivery) {
                $this->addFlash('error', 'Veuillez sélectionner une adresse de livraison.');
                return $this->redirectToRoute('app_order');
            }
    
            $deliveryContent = sprintf(
                "%s %s\n%s\n%s%s\n%s %s\n%s",
                $delivery->getFirstname(),
                $delivery->getLastname(),
                $delivery->getPhone(),
                $delivery->getCompany() ? $delivery->getCompany() . "\n" : '',
                $delivery->getAdress(),
                $delivery->getPostal(),
                $delivery->getCity(),
                $delivery->getCountry()
            );
    
            $order = new Order();
            $reference = $date->format('dmY') . '-' . uniqid();
            $order->setReference($reference)
                ->setUser($user)
                ->setCreatedAt($date)
                ->setCarrierName($carrier->getName())
                ->setCarrierPrice($carrier->getPrice())
                ->setDelivery($deliveryContent)
                ->setStripeSessionId('0')
                ->setIsPaid(false);
    
            $this->entityManager->persist($order);
    
            $total = 0;
            foreach ($cart->getFull() as $item) {
                $product = $item['product'];
    
                $orderDetail = new OrderDetail();
                $orderDetail->setMyOrder($order)
                    ->setProduct($product->getNomProduit())
                    ->setQuantity($item['quantity'])
                    ->setPrice($product->getPrix())
                    ->setTotal($product->getPrix() * $item['quantity']);
    
                $this->entityManager->persist($orderDetail);
    
                $total += $product->getPrix() * $item['quantity'];
            }
    
            $this->entityManager->flush();
    
            // ✅ Now Generate QR Code correctly
            $qrContent = $this->generateQrContent($order, $delivery, $carrier, $cart->getFull());
    
            $qrCodeDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads/qrcodes/';
            if (!file_exists($qrCodeDirectory)) {
                mkdir($qrCodeDirectory, 0777, true);
            }
    
            $qrCodePath = 'uploads/qrcodes/' . $reference . '.png';
    
            // Proper QrCode creation
            $qrCode = new QrCode($qrContent);
    
            // Configure QR code manually if needed
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
    
            $result->saveToFile($this->getParameter('kernel.project_dir') . '/public/' . $qrCodePath);
    
            return $this->render('order/add.html.twig', [
                'fullCarts' => $cart->getFull(),
                'carrier' => $carrier,
                'delivery' => $deliveryContent,
                'reference' => $reference,
                'qrCodePath' => $qrCodePath,
                'total' => $total
            ]);
        }
    
        return $this->redirectToRoute('app_panier');
    }
    
    private function generateQrContent(Order $order, $delivery, $carrier, $fullCarts): string
    {
        $qrContent = "Détails de la commande\n";
        $qrContent .= "Référence: " . $order->getReference() . "\n\n";

        $qrContent .= "[👤] Informations client\n";
        $qrContent .= "👤 " . $delivery->getFirstname() . ' ' . $delivery->getLastname() . "\n";
        $qrContent .= "📞 " . $delivery->getPhone() . "\n\n";

        $qrContent .= "[📍] Adresse\n";
        if ($delivery->getCompany()) {
            $qrContent .= "🏢 " . $delivery->getCompany() . "\n";
        }
        $qrContent .= "📍 " . $delivery->getAdress() . "\n";
        $qrContent .= "   " . $delivery->getPostal() . ' ' . $delivery->getCity() . "\n";
        $qrContent .= "   " . $delivery->getCountry() . "\n\n";

        $qrContent .= "[🚚] Transporteur\n";
        $qrContent .= "🚚 " . $carrier->getName() . "\n";
        $qrContent .= "💰 Frais: " . number_format($carrier->getPrice() / 100, 2, ',', ' ') . " TND\n\n";

        $qrContent .= "[🛒] Produits commandés\n";
        if (count($fullCarts) > 0) {
            foreach ($fullCarts as $item) {
                $product = $item['product'];
                $qrContent .= "✔️ " . $product->getNomProduit() . " - ";
                $qrContent .= $item['quantity'] . " × ";
                $qrContent .= number_format($product->getPrix() / 100, 2, ',', ' ') . " TND\n";
            }
        } else {
            $qrContent .= "ℹ️ Aucun produit dans cette commande\n";
        }
        $qrContent .= "\n";

        $total = array_reduce($fullCarts, function ($carry, $item) {
            return $carry + ($item['product']->getPrix() * $item['quantity']);
        }, 0);

        $qrContent .= "[🧾] Récapitulatif\n";
        $qrContent .= "Sous-total: " . number_format($total / 100, 2, ',', ' ') . " TND\n";
        $qrContent .= "Frais de livraison: " . number_format($carrier->getPrice() / 100, 2, ',', ' ') . " TND\n";
        $qrContent .= "Total: " . number_format(($total + $carrier->getPrice()) / 100, 2, ',', ' ') . " TND";

        return $qrContent;
    }
}
