<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Models\Product;
use App\Models\Swipe;
use App\Models\Matched;
use App\Models\Conversation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = 0;
    protected static string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [];
    }

    public function getColumns(): int | string | array
    {
        return 2;
    }

    /**
     * Get overview metrics
     */
    public function getOverviewMetrics(): array
    {
        $userId = auth()->id();

        return [
            'listings' => [
                'total' => Product::where('user_id', $userId)->count(),
                'active' => Product::where('user_id', $userId)->where('status', 'available')->count(),
                'sold' => Product::where('user_id', $userId)->where('status', 'sold')->count(),
            ],
            'engagement' => [
                'views' => Product::where('user_id', $userId)->sum('view_count'),
                'swipes' => Swipe::where('user_id', $userId)->count(),
                'matches' => Matched::where('buyer_id', $userId)->orWhere('seller_id', $userId)->count(),
            ],
            'revenue' => [
                'total' => Product::where('user_id', $userId)->where('status', 'sold')->sum('price'),
                'pending' => Product::where('user_id', $userId)->where('status', 'reserved')->sum('price'),
            ],
        ];
    }

    /**
     * Get activity data for the last 7 days
     */
    public function getRecentActivity(): array
    {
        $userId = auth()->id();
        $days = 7;
        $startDate = Carbon::now()->subDays($days);

        $dailySwipes = Swipe::where('user_id', $userId)
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

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

        return [
            'swipes' => $dailySwipes,
            'matches' => $dailyMatches,
            'period' => $days,
        ];
    }

    /**
     * Get top performing products
     */
    public function getTopProducts(): array
    {
        $userId = auth()->id();

        return Product::where('user_id', $userId)
            ->where('status', 'available')
            ->orderByDesc('view_count')
            ->limit(5)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'price' => $product->price,
                    'views' => $product->view_count,
                    'likes' => $product->swipes()->where('direction', 'right')->count(),
                    'image' => $product->images[0] ?? null,
                ];
            })
            ->toArray();
    }

    /**
     * Get unread messages count
     */
    public function getUnreadMessagesCount(): int
    {
        $userId = auth()->id();

        return Conversation::whereHas('matched', function ($query) use ($userId) {
            $query->where('buyer_id', $userId)->orWhere('seller_id', $userId);
        })
        ->whereHas('messages', function ($query) use ($userId) {
            $query->where('user_id', '!=', $userId)->where('is_read', false);
        })
        ->count();
    }

    /**
     * Get quick actions based on user status
     */
    public function getQuickActions(): array
    {
        $metrics = $this->getOverviewMetrics();
        $actions = [];

        if ($metrics['listings']['active'] === 0) {
            $actions[] = [
                'title' => 'Create Your First Listing',
                'description' => 'Start selling by creating your first product',
                'url' => route('filament.app.resources.products.create'),
                'icon' => 'heroicon-o-plus-circle',
                'color' => 'primary',
            ];
        }

        if ($metrics['engagement']['matches'] > 0 && $this->getUnreadMessagesCount() > 0) {
            $actions[] = [
                'title' => 'You Have Unread Messages',
                'description' => 'Check your conversations to connect with buyers',
                'url' => route('filament.app.pages.conversations-page'),
                'icon' => 'heroicon-o-chat-bubble-left-right',
                'color' => 'warning',
            ];
        }

        if ($metrics['engagement']['swipes'] < 10) {
            $actions[] = [
                'title' => 'Start Discovering Products',
                'description' => 'Swipe through available items in your area',
                'url' => route('filament.app.pages.swiping-page'),
                'icon' => 'heroicon-o-hand-raised',
                'color' => 'success',
            ];
        }

        return $actions;
    }

    /**
     * Calculate performance insights
     */
    public function getPerformanceInsights(): array
    {
        $metrics = $this->getOverviewMetrics();
        $insights = [];

        // Calculate conversion rate
        if ($metrics['engagement']['swipes'] > 0) {
            $conversionRate = ($metrics['engagement']['matches'] / $metrics['engagement']['swipes']) * 100;
            $insights['conversion'] = round($conversionRate, 1);
        } else {
            $insights['conversion'] = 0;
        }

        // Calculate average views per listing
        if ($metrics['listings']['total'] > 0) {
            $insights['avg_views'] = round($metrics['engagement']['views'] / $metrics['listings']['total']);
        } else {
            $insights['avg_views'] = 0;
        }

        // Calculate sell-through rate
        if ($metrics['listings']['total'] > 0) {
            $insights['sell_through'] = round(($metrics['listings']['sold'] / $metrics['listings']['total']) * 100, 1);
        } else {
            $insights['sell_through'] = 0;
        }

        return $insights;
    }

    protected function getViewData(): array
    {
        return array_merge(parent::getViewData(), [
            'overview' => $this->getOverviewMetrics(),
            'activity' => $this->getRecentActivity(),
            'topProducts' => $this->getTopProducts(),
            'unreadMessages' => $this->getUnreadMessagesCount(),
            'quickActions' => $this->getQuickActions(),
            'insights' => $this->getPerformanceInsights(),
        ]);
    }
}