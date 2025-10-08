<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Models\Product;
use App\Models\Swipe;
use App\Models\Matched;
use Illuminate\Support\Facades\Auth;
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
     * Get comprehensive user statistics
     */
    public function getStats(): array
    {
        $userId = Auth::id();

        return [
            'total_products' => Product::where('user_id', $userId)->count(),
            'active_products' => Product::where('user_id', $userId)
                ->where('status', 'available')
                ->count(),
            'sold_products' => Product::where('user_id', $userId)
                ->where('status', 'sold')
                ->count(),
            'total_swipes' => Swipe::where('user_id', $userId)->count(),
            'right_swipes' => Swipe::where('user_id', $userId)
                ->where('direction', 'right')
                ->count(),
            'total_matches' => Matched::where('buyer_id', $userId)
                ->orWhere('seller_id', $userId)
                ->count(),
            'total_views' => Product::where('user_id', $userId)->sum('view_count'),
            'total_revenue' => Product::where('user_id', $userId)
                ->where('status', 'sold')
                ->sum('price'),
        ];
    }

    /**
     * Get recent matches with relationships
     */
    public function getRecentMatches()
    {
        $userId = Auth::id();

        return Matched::where('buyer_id', $userId)
            ->orWhere('seller_id', $userId)
            ->with(['product', 'buyer', 'seller'])
            ->latest()
            ->limit(5)
            ->get();
    }

    /**
     * Calculate conversion metrics
     */
    public function getSuccessRate(): int
    {
        $stats = $this->getStats();
        
        if ($stats['right_swipes'] > 0) {
            return round(($stats['total_matches'] / $stats['right_swipes']) * 100);
        }
        
        return 0;
    }

    /**
     * Calculate engagement metrics
     */
    public function getAverageViews(): int
    {
        $stats = $this->getStats();
        
        if ($stats['total_products'] > 0) {
            return round($stats['total_views'] / $stats['total_products']);
        }
        
        return 0;
    }

    /**
     * Calculate listing health
     */
    public function getActiveRate(): int
    {
        $stats = $this->getStats();
        
        if ($stats['total_products'] > 0) {
            return round(($stats['active_products'] / $stats['total_products']) * 100);
        }
        
        return 0;
    }

    /**
     * Get trend data for the last 7 days
     */
    public function getTrendData(): array
    {
        $userId = Auth::id();
        $weekAgo = Carbon::now()->subDays(7);

        $newMatches = Matched::where(function($query) use ($userId) {
                $query->where('buyer_id', $userId)
                      ->orWhere('seller_id', $userId);
            })
            ->where('created_at', '>=', $weekAgo)
            ->count();

        $previousWeekMatches = Matched::where(function($query) use ($userId) {
                $query->where('buyer_id', $userId)
                      ->orWhere('seller_id', $userId);
            })
            ->whereBetween('created_at', [Carbon::now()->subDays(14), $weekAgo])
            ->count();

        $matchTrend = $previousWeekMatches > 0 
            ? round((($newMatches - $previousWeekMatches) / $previousWeekMatches) * 100)
            : ($newMatches > 0 ? 100 : 0);

        return [
            'matches_this_week' => $newMatches,
            'match_trend' => $matchTrend,
        ];
    }

    /**
     * Get performance insights
     */
    public function getInsights(): array
    {
        $stats = $this->getStats();
        $insights = [];

        if ($stats['active_products'] === 0) {
            $insights[] = [
                'type' => 'warning',
                'title' => 'No Active Listings',
                'message' => 'Create your first listing to start connecting with buyers.',
                'action' => 'Create Listing',
                'action_url' => route('filament.app.resources.products.create'),
            ];
        }

        if ($stats['total_matches'] > 0 && $stats['sold_products'] === 0) {
            $insights[] = [
                'type' => 'info',
                'title' => 'Matches Available',
                'message' => 'You have matches waiting. Start conversations to close deals.',
                'action' => 'View Matches',
                'action_url' => route('filament.app.pages.conversations-page'),
            ];
        }

        $successRate = $this->getSuccessRate();
        if ($successRate > 50 && $stats['right_swipes'] > 10) {
            $insights[] = [
                'type' => 'success',
                'title' => 'High Performance',
                'message' => "Your {$successRate}% match rate is excellent. Keep it up!",
            ];
        }

        return $insights;
    }

    /**
     * Aggregate view data for the template
     */
    protected function getViewData(): array
    {
        return array_merge(parent::getViewData(), [
            'stats' => $this->getStats(),
            'recentMatches' => $this->getRecentMatches(),
            'successRate' => $this->getSuccessRate(),
            'averageViews' => $this->getAverageViews(),
            'activeRate' => $this->getActiveRate(),
            'trendData' => $this->getTrendData(),
            'insights' => $this->getInsights(),
        ]);
    }
}