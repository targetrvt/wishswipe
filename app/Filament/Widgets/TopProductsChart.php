<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TopProductsChart extends ApexChartWidget
{
    protected static ?string $chartId = 'topProductsChart';
    protected static ?int $contentHeight = 350;
    protected static bool $isCollapsible = true;

    public function getHeading(): ?string
    {
        return __('dashboard.charts.top_products.heading');
    }

    public function getSubheading(): ?string
    {
        return __('dashboard.charts.top_products.subheading');
    }

    protected function getOptions(): array
    {
        $userId = auth()->id();

        $topProducts = Product::where('user_id', $userId)
            ->where('status', 'available')
            ->orderByDesc('view_count')
            ->limit(5)
            ->get();

        $labels = [];
        $viewData = [];
        $likeData = [];

        foreach ($topProducts as $product) {
            $labels[] = strlen($product->title) > 20 ? substr($product->title, 0, 20) . '...' : $product->title;
            $viewData[] = $product->view_count;
            $likeData[] = $product->swipes()->where('direction', 'right')->count();
        }

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 350,
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'series' => [
                [
                    'name' => __('dashboard.charts.top_products.series.views'),
                    'data' => $viewData,
                ],
                [
                    'name' => __('dashboard.charts.top_products.series.likes'),
                    'data' => $likeData,
                ],
            ],
            'xaxis' => [
                'categories' => $labels,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                        'fontWeight' => 600,
                    ],
                    'rotate' => -45,
                    'rotateAlways' => true,
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#8b5cf6', '#ec4899'],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => false,
                    'columnWidth' => '55%',
                    'endingShape' => 'rounded',
                ],
            ],
            'dataLabels' => [
                'enabled' => true,
            ],
            'legend' => [
                'position' => 'top',
                'horizontalAlign' => 'left',
            ],
        ];
    }
}

