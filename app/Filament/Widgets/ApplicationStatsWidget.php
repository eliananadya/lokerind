<?php

namespace App\Filament\Widgets;

use App\Models\Applications;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ApplicationStatsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $totalApplications = Applications::count();
        $pendingApplications = Applications::where('status', 'pending')->count();
        $approvedApplications = Applications::where('status', 'approved')->count();
        $rejectedApplications = Applications::where('status', 'rejected')->count();

        // Hitung persentase approval rate
        $approvalRate = $totalApplications > 0
            ? round(($approvedApplications / $totalApplications) * 100, 1)
            : 0;

        // Hitung aplikasi bulan ini
        $thisMonthApplications = Applications::whereMonth('applied_at', now()->month)
            ->whereYear('applied_at', now()->year)
            ->count();

        // Hitung aplikasi bulan lalu untuk trend
        $lastMonthApplications = Applications::whereMonth('applied_at', now()->subMonth()->month)
            ->whereYear('applied_at', now()->subMonth()->year)
            ->count();

        $trend = $lastMonthApplications > 0
            ? round((($thisMonthApplications - $lastMonthApplications) / $lastMonthApplications) * 100, 1)
            : 0;

        return [
            Stat::make('Total Lamaran', $totalApplications)
                ->description($thisMonthApplications . ' lamaran bulan ini')
                ->descriptionIcon($trend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($trend >= 0 ? 'success' : 'danger')
                ->chart($this->getChartData())
                ->icon('heroicon-o-document-text'),

            Stat::make('Pending', $pendingApplications)
                ->description('Menunggu review')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->icon('heroicon-o-clock'),

            Stat::make('Diterima', $approvedApplications)
                ->description("Approval rate: {$approvalRate}%")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            Stat::make('Ditolak', $rejectedApplications)
                ->description('Total penolakan')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger')
                ->icon('heroicon-o-x-circle'),
        ];
    }

    protected function getChartData(): array
    {
        // Data 7 hari terakhir
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = Applications::whereDate('applied_at', $date)->count();
            $data[] = $count;
        }
        return $data;
    }
}
