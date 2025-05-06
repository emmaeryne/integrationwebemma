<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande', name: 'app_order')]
    public function order(Cart $cart): Response
    {
        // Vérification des stocks avant d'accéder à la commande
        $fullCarts = $cart->getfull();
        foreach ($fullCarts as $item) {
            if ($item['quantity'] > $item['product']->getStockDispo()) {
                $this->addFlash('error', 'La quantité demandée pour le produit "'.$item['product']->getNomProduit().'" dépasse le stock disponible.');
                return $this->redirectToRoute('app_panier');
            }
        }
    
        if(!$this->getUser()->getAdresses()->getValues()) {
            return $this->redirectToRoute('app_account_add_adress');
        }
        
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);
    
        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cartProducts' => $fullCarts
        ]);
    }
     
    #[Route('/commande/recap', name: 'app_order_recap', methods: ['POST'])]
    public function add(Cart $cart, Request $request): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTimeImmutable('now');
            $carriers = $form->get('carriers')->getData();
            $delivery = $form->get('addresses')->getData();
            
            // Format delivery content
            $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();
            $delivery_content .= '\n'.$delivery->getPhone();

            if ($delivery->getCompany()) {
                $delivery_content .= '\n'.$delivery->getCompany();
            }

            $delivery_content .= '\n'.$delivery->getAdress();
            $delivery_content .= '\n'.$delivery->getPostal().' '.$delivery->getCity();
            $delivery_content .= '\n'.$delivery->getCountry();

            // Create order
            $order = new Order();
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setStripeSessionId('0');
            $order->setIsPaid(0);

            $this->entityManager->persist($order);

            // Add order details
            $total = 0;
            foreach ($cart->getFull() as $product) {
                $orderDetails = new OrderDetail();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getNomProduit());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrix());
                $orderDetails->setTotal($product['product']->getPrix() * $product['quantity']);
                $this->entityManager->persist($orderDetails);
                
                $total += $product['product']->getPrix() * $product['quantity'];
            }

            $this->entityManager->flush();

            // Generate QR code content
            $qrContent = $this->generateQrContent($order, $delivery, $carriers, $cart->getFull());
            
            // Create QR code
            $qrCode = Builder::create()
                ->writer(new PngWriter())
                ->data($qrContent)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(ErrorCorrectionLevel::High)
                ->size(300)
                ->margin(10)
                ->build();

            // Save QR code
            $qrCodeDirectory = $this->getParameter('kernel.project_dir').'/public/uploads/qrcodes/';
            if (!file_exists($qrCodeDirectory)) {
                mkdir($qrCodeDirectory, 0777, true);
            }
            
            $qrCodePath = 'uploads/qrcodes/'.$reference.'.png';
            $qrCode->saveToFile($this->getParameter('kernel.project_dir').'/public/'.$qrCodePath);

            return $this->render('order/add.html.twig', [
                'fullCarts' => $cart->getFull(),
                'carrier' => $carriers,
                'delivery' => $delivery_content,
                'reference' => $reference,
                'qrCodePath' => $qrCodePath,
                'total' => $total
            ]);
        }

        return $this->redirectToRoute('app_panier');
    }

    private function generateQrContent(Order $order, $delivery, $carriers, $fullCarts): string
    {
        $qrContent = "Détails de la commande\n";
        $qrContent .= "Référence: ".$order->getReference()."\n\n";
        
        // Client information
        $qrContent .= "[👤] Informations client\n";
        $qrContent .= "👤 ".$delivery->getFirstname().' '.$delivery->getLastname()."\n";
        $qrContent .= "📞 ".$delivery->getPhone()."\n\n";
        
        // Delivery address
        $qrContent .= "[📍] Adresse\n";
        if ($delivery->getCompany()) {
            $qrContent .= "🏢 ".$delivery->getCompany()."\n";
        }
        $qrContent .= "📍 ".$delivery->getAdress()."\n";
        $qrContent .= "   ".$delivery->getPostal().' '.$delivery->getCity()."\n";
        $qrContent .= "   ".$delivery->getCountry()."\n\n";
        
        // Carrier information
        $qrContent .= "[🚚] Transporteur\n";
        $qrContent .= "🚚 ".$carriers->getName()."\n";
        $qrContent .= "💰 Frais: ".number_format($carriers->getPrice() / 100, 2, ',', ' ')." TND\n\n";
        
        // Products
        $qrContent .= "[🛒] Produits commandés\n";
        if (count($fullCarts) > 0) {
            foreach ($fullCarts as $item) {
                $product = $item['product'];
                $qrContent .= "✔️ ".$product->getNomProduit()." - ";
                $qrContent .= $item['quantity']." × ";
                $qrContent .= number_format($product->getPrix() / 100, 2, ',', ' ')." TND\n";
            }
        } else {
            $qrContent .= "ℹ️ Aucun produit dans cette commande\n";
        }
        $qrContent .= "\n";
        
        // Summary
        $total = array_reduce($fullCarts, function($carry, $item) {
            return $carry + ($item['product']->getPrix() * $item['quantity']);
        }, 0);
        
        $qrContent .= "[🧾] Récapitulatif\n";
        $qrContent .= "Sous-total: ".number_format($total / 100, 2, ',', ' ')." TND\n";
        $qrContent .= "Frais de livraison: ".number_format($carriers->getPrice() / 100, 2, ',', ' ')." TND\n";
        $qrContent .= "Total: ".number_format(($total + $carriers->getPrice()) / 100, 2, ',', ' ')." TND";
        
        return $qrContent;
    }
}