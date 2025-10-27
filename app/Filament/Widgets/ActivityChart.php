<?php

namespace App\Filament\Widgets;

use App\Models\Swipe;
use App\Models\Matched;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ActivityChart extends ApexChartWidget
{
    protected static ?string $chartId = 'activityChart';
    protected static ?int $contentHeight = 300;
    protected static bool $isCollapsible = true;

    public function getHeading(): ?string
    {
        return __('dashboard.charts.activity.heading');
    }

    public function getSubheading(): ?string
    {
        return __('dashboard.charts.activity.subheading');
    }

    protected function getOptions(): array
    {
        $userId = auth()->id();
        $days = 7;
        $startDate = Carbon::now()->subDays($days);

        // Get daily swipes
        $dailySwipes = Swipe::where('user_id', $userId)
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Get daily matches
        $dailyMatches = Matched::where(function($query) use ($userId) {
                $query->where('buyer_id', $userId)->orWhere('seller_id', $userId);
            })
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Generate labels for last 7 days
        $labels = [];
        $swipeData = [];
        $matchData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dayName = Carbon::now()->subDays($i)->format('D');
            $labels[] = $dayName;
            $swipeData[] = $dailySwipes[$date] ?? 0;
            $matchData[] = $dailyMatches[$date] ?? 0;
        }

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'series' => [
                [
                    'name' => __('dashboard.charts.activity.series.swipes'),
                    'data' => $swipeData,
                ],
                [
                    'name' => __('dashboard.charts.activity.series.matches'),
                    'data' => $matchData,
                ],
            ],
            'xaxis' => [
                'categories' => $labels,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#3b82f6', '#10b981'],
            'stroke' => [
                'width' => 3,
                'curve' => 'smooth',
            ],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'shade' => 'light',
                    'type' => 'vertical',
                    'shadeIntensity' => 0.3,
                    'gradientToColors' => ['#60a5fa', '#34d399'],
                    'inverseColors' => false,
                    'opacityFrom' => 0.7,
                    'opacityTo' => 0.1,
                ],
            ],
            'markers' => [
                'size' => 5,
                'hover' => [
                    'size' => 7,
                ],
            ],
            'legend' => [
                'position' => 'top',
                'horizontalAlign' => 'left',
            ],
        ];
    }
}

