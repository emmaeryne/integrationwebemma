<?php

namespace App\Controller\Admin;

use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/admin')]
class DashboardSalmaController extends AbstractController
{
    private ChartBuilderInterface $chartBuilder;

    public function __construct(ChartBuilderInterface $chartBuilder)
    {
        $this->chartBuilder = $chartBuilder;
    }

    #[Route('/', name: 'admin_dashboard')]
    public function index(
        OrderRepository $orderRepository,
        UserRepository $userRepository,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    ): Response {
        $orderStats = $this->getOrderStatistics($orderRepository);

        $paymentStatusChart = $this->createPaymentStatusChart(
            $orderStats['paid'],
            $orderStats['unpaid']
        );

        $recentOrders = $orderRepository->findBy([], ['createdAt' => 'DESC'], 5);

        return $this->render('admin/dashboard/index.html.twig', [
            'totalOrders' => $orderStats['total'],
            'totalUsers' => $userRepository->count([]),
            'totalProducts' => $productRepository->count([]),
            'totalCategories' => $categoryRepository->count([]),
            'paidOrders' => $orderStats['paid'],
            'unpaidOrders' => $orderStats['unpaid'],
            'paidPercentage' => $orderStats['paidPercentage'],
            'unpaidPercentage' => $orderStats['unpaidPercentage'],
            'paymentStatusChart' => $paymentStatusChart,
            'recentOrders' => $recentOrders,
        ]);
    }

    private function getOrderStatistics(OrderRepository $orderRepository): array
    {
        $paidOrders = $orderRepository->count(['isPaid' => true]);
        $unpaidOrders = $orderRepository->count(['isPaid' => false]);
        $totalOrders = $paidOrders + $unpaidOrders;

        return [
            'paid' => $paidOrders,
            'unpaid' => $unpaidOrders,
            'total' => $totalOrders,
            'paidPercentage' => $totalOrders > 0 ? round(($paidOrders / $totalOrders) * 100) : 0,
            'unpaidPercentage' => $totalOrders > 0 ? round(($unpaidOrders / $totalOrders) * 100) : 0,
        ];
    }

    private function createPaymentStatusChart(int $paidOrders, int $unpaidOrders): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        $chart->setData([
            'labels' => ['Payées', 'Non payées'],
            'datasets' => [[
                'label' => 'Statut des paiements',
                'data' => [$paidOrders, $unpaidOrders],
                'backgroundColor' => ['#28d094', '#ff4d4f'],
                'borderColor' => ['#ffffff', '#ffffff'],
                'borderWidth' => 2,
            ]],
        ]);

        $chart->setOptions([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'right',
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Répartition des paiements',
                    'font' => [
                        'size' => 16,
                    ],
                ],
            ],
            'cutout' => '70%',
        ]);

        return $chart;
    }
}
