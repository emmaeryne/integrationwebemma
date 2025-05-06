<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Form\OrderType1;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
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
    #[Route('/search/ajax', name: 'admin_order_search_ajax', methods: ['GET'])]
    public function searchAjax(Request $request, OrderRepository $orderRepository): JsonResponse
    {
        $searchTerm = trim($request->query->get('term', ''));
        $page = $request->query->getInt('page', 1);
        $limit = 10;
        
        if (empty($searchTerm)) {
            return $this->json([
                'results' => [],
                'pagination' => ['more' => false]
            ]);
        }
        
        $orders = $orderRepository->findBySearch($searchTerm, ($page - 1) * $limit, $limit);
        $total = $orderRepository->countSearchResults($searchTerm);
        
        $results = array_map(function($order) {
            return [
                'id' => $order->getId(),
                'text' => sprintf(
                    "#%d - %s %s (%s) - %s - %s TND",
                    $order->getId(),
                    $order->getUser()->getFirstname(),
                    $order->getUser()->getLastname(),
                    $order->getReference(),
                    $order->getCreatedAt()->format('d/m/Y'),
                    number_format(($order->getTotal() / 100), 2, ',', ' ')
                ),
                'isPaid' => $order->isPaid()
            ];
        }, $orders);
        
        return $this->json([
            'results' => $results,
            'pagination' => [
                'more' => ($page * $limit) < $total
            ]
        ]);
    }
    #[Route('/', name: 'admin_order_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository, Request $request): Response
    {
        $currentPage = $request->query->getInt('page', 1);
        $totalOrders = $orderRepository->count([]);
        $totalPages = ceil($totalOrders / self::ITEMS_PER_PAGE);
        
        $orders = $orderRepository->findBy(
            [],
            ['id' => 'DESC'],
            self::ITEMS_PER_PAGE,
            ($currentPage - 1) * self::ITEMS_PER_PAGE
        );
    
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
        $form = $this->createForm(OrderType1::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('admin_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/order/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
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

    #[Route('/search', name: 'admin_order_search', methods: ['GET'])]
    public function search(Request $request, OrderRepository $orderRepository): JsonResponse
    {
        $query = $request->query->get('q', '');
        $orders = $orderRepository->searchOrders($query);
        
        $results = [];
        foreach ($orders as $order) {
            $results[] = [
                'id' => $order->getId(),
                'text' => sprintf(
                    "#%d - %s %s (%s) - %s",
                    $order->getId(),
                    $order->getUser()->getFirstname(),
                    $order->getUser()->getLastname(),
                    $order->getReference(),
                    $order->getCreatedAt()->format('d/m/Y')
                ),
                'url' => $this->generateUrl('admin_order_show', ['id' => $order->getId()])
            ];
        }
        
        return $this->json($results);
    }
}