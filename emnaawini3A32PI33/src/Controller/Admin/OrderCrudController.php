<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Form\OrderType1;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Snappy\Pdf;

#[Route('/admin/order')]
class OrderCrudController extends AbstractController
{
    private const ITEMS_PER_PAGE = 10;
    private Pdf $knpSnappyPdf;

    public function __construct(Pdf $knpSnappyPdf)
    {
        $this->knpSnappyPdf = $knpSnappyPdf;
        $this->knpSnappyPdf->setBinary('"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe"');
    }

    #[Route('/', name: 'admin_order_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository, Request $request): Response
    {
        $currentPage = $request->query->getInt('page', 1);
        
        // Récupérer les paramètres de filtrage et de tri
        $filters = [
            'status' => $request->query->get('status'),
            'date' => $request->query->get('date'),
        ];
        $sortBy = $request->query->get('sortBy', 'id');
        $sortOrder = $request->query->get('sortOrder', 'DESC');

        // Nettoyer les filtres vides
        $filters = array_filter($filters, fn($value) => !empty($value));

        // Récupérer les commandes filtrées et triées
        $orders = $orderRepository->findByFilters(
            $filters,
            $sortBy,
            $sortOrder,
            ($currentPage - 1) * self::ITEMS_PER_PAGE,
            self::ITEMS_PER_PAGE
        );

        // Compter le nombre total de commandes pour la pagination
        $totalOrders = $orderRepository->countByFilters($filters);
        $totalPages = ceil($totalOrders / self::ITEMS_PER_PAGE);

        // Statistiques
        $paidCount = $orderRepository->count(['isPaid' => true]);
        $unpaidCount = $orderRepository->count(['isPaid' => false]);
        $timeStats = $orderRepository->getDailyAndWeeklyOrderStats(30);

        return $this->render('admin/order/index.html.twig', [
            'orders' => $orders,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'paidCount' => $paidCount,
            'unpaidCount' => $unpaidCount,
            'dailyStats' => $timeStats['daily'],
            'weeklyStats' => $timeStats['weekly'],
        ]);
    }

    #[Route('/pdf/{status}', name: 'admin_order_pdf', methods: ['GET'])]
    public function generatePdf(OrderRepository $orderRepository, string $status): Response
    {
        $orders = $orderRepository->findBy(
            ['isPaid' => $status === 'paid'],
            ['id' => 'DESC']
        );

        $html = $this->renderView('admin/order/pdf.html.twig', [
            'orders' => $orders,
            'status' => $status
        ]);

        $filename = sprintf('commandes_%s_%s.pdf', $status, date('Y-m-d'));

        return new PdfResponse(
            $this->knpSnappyPdf->getOutputFromHtml($html),
            $filename
        );
    }

    #[Route('/{id}', name: 'admin_order_show', methods: ['GET'])]
    public function show(Order $order): Response
    {
        return $this->render('admin/order/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            // Validate CSRF token
            if (!$this->isCsrfTokenValid('edit' . $order->getId(), $request->request->get('_token'))) {
                throw $this->createAccessDeniedException('Invalid CSRF token.');
            }

            // Get form data
            $carrierName = $request->request->get('carrierName');
            $carrierPrice = $request->request->get('carrierPrice');
            $isPaid = $request->request->has('isPaid');

            // Basic validation
            if (empty($carrierName) || !is_numeric($carrierPrice) || $carrierPrice < 0) {
                $this->addFlash('error', 'Veuillez remplir tous les champs correctement.');
                return $this->render('admin/order/edit.html.twig', [
                    'order' => $order,
                ]);
            }

            // Update the order entity
            $order->setCarrierName($carrierName);
            $order->setCarrierPrice((float) $carrierPrice);
            $order->setIsPaid($isPaid);

            // Persist changes
            $entityManager->flush();

            $this->addFlash('success', 'Commande mise à jour avec succès.');
            return $this->redirectToRoute('admin_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/order/edit.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}', name: 'admin_order_delete', methods: ['POST'])]
    public function delete(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_order_index', [], Response::HTTP_SEE_OTHER);
    }
}