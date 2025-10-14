<?php

namespace App\Filament\Widgets;

use App\Models\Review;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReviewStatsWidget extends BaseWidget
{
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $total = Review::count();
        $pending = Review::pending()->count();
        $approved = Review::approved()->count();
        $rejected = Review::rejected()->count();

        $approvalRate = $total > 0 ? round(($approved / $total) * 100) : 0;

        return [
            Stat::make('Total Reviews', (string) $total)
                ->description('All-time total')
                ->icon('heroicon-o-rectangle-stack'),

            Stat::make('Pending Reviews', (string) $pending)
                ->description('Awaiting moderation')
                ->color($pending > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-clock'),

            Stat::make('Approval Rate', $approvalRate . '%')
                ->description($approved . ' approved / ' . $total . ' total')
                ->color('success')
                ->icon('heroicon-o-check-badge'),

            Stat::make('Rejected Reviews', (string) $rejected)
                ->description('Rejected by moderators')
                ->color('danger')
                ->icon('heroicon-o-x-mark'),
        ];
    }
}
