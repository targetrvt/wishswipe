<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class StatsChart extends ApexChartWidget
{
    protected static ?string $chartId = 'statsChart';
    protected static ?int $contentHeight = 300;
    protected static bool $isCollapsible = true;

    public function getHeading(): ?string
    {
        return __('dashboard.charts.stats.heading');
    }

    public function getSubheading(): ?string
    {
        return __('dashboard.charts.stats.subheading');
    }

    protected function getOptions(): array
    {
        $userId = auth()->id();

        $active = Product::where('user_id', $userId)->where('status', 'available')->count();
        $sold = Product::where('user_id', $userId)->where('status', 'sold')->count();
        $reserved = Product::where('user_id', $userId)->where('status', 'reserved')->count();

        return [
            'chart' => [
                'type' => 'donut',
                'height' => 300,
            ],
            'series' => [$active, $sold, $reserved],
            'labels' => [
                __('dashboard.charts.stats.labels.active'),
                __('dashboard.charts.stats.labels.sold'),
                __('dashboard.charts.stats.labels.reserved'),
            ],
            'colors' => ['#3b82f6', '#10b981', '#f59e0b'],
            'legend' => [
                'position' => 'bottom',
                'horizontalAlign' => 'center',
            ],
            'plotOptions' => [
                'pie' => [
                    'donut' => [
                        'size' => '65%',
                        'labels' => [
                            'show' => true,
                            'name' => [
                                'show' => true,
                                'fontSize' => '16px',
                                'fontWeight' => 600,
                            ],
                            'value' => [
                                'show' => true,
                                'fontSize' => '14px',
                            ],
                            'total' => [
                                'show' => true,
                                'label' => 'Total',
                                'fontSize' => '16px',
                                'fontWeight' => 600,
                            ],
                        ],
                    ],
                ],
            ],
            'dataLabels' => [
                'enabled' => true,
            ],
        ];
    }
}

