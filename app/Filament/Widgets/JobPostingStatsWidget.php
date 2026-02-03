<?php

namespace App\Filament\Widgets;

use App\Models\JobPostings;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class JobPostingStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalJobs = JobPostings::count();
        $pendingJobs = JobPostings::where('verification_status', 'Pending')->count();
        $approvedJobs = JobPostings::where('verification_status', 'Approved')->count();
        $rejectedJobs = JobPostings::where('verification_status', 'Rejected')->count();
        $openJobs = JobPostings::where('status', 'Open')->count();

        // Hitung perubahan dalam 7 hari terakhir
        $pendingTrend = $this->calculateTrend('Pending');
        $approvedTrend = $this->calculateTrend('Approved');

        return [
            Stat::make('Total Lowongan', $totalJobs)
                ->description('Total semua lowongan kerja')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('primary')
                ->chart($this->getTrendData(7)),

            Stat::make('Menunggu Verifikasi', $pendingJobs)
                ->description($pendingTrend >= 0 ? "+{$pendingTrend} dari minggu lalu" : "{$pendingTrend} dari minggu lalu")
                ->descriptionIcon($pendingTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color('warning')
                ->chart($this->getTrendData(7, 'Pending')),

            Stat::make('Disetujui', $approvedJobs)
                ->description($approvedTrend >= 0 ? "+{$approvedTrend} dari minggu lalu" : "{$approvedTrend} dari minggu lalu")
                ->descriptionIcon($approvedTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color('success')
                ->chart($this->getTrendData(7, 'Approved')),

            Stat::make('Lowongan Aktif', $openJobs)
                ->description('Lowongan yang sedang buka')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Ditolak', $rejectedJobs)
                ->description('Total lowongan yang ditolak')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }

    private function calculateTrend(string $status): int
    {
        $currentWeek = JobPostings::where('verification_status', $status)
            ->whereBetween('created_at', [now()->subWeek(), now()])
            ->count();

        $previousWeek = JobPostings::where('verification_status', $status)
            ->whereBetween('created_at', [now()->subWeeks(2), now()->subWeek()])
            ->count();

        return $currentWeek - $previousWeek;
    }

    private function getTrendData(int $days = 7, ?string $status = null): array
    {
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $query = JobPostings::whereDate('created_at', $date);

            if ($status) {
                $query->where('verification_status', $status);
            }

            $data[] = $query->count();
        }

        return $data;
    }
}
