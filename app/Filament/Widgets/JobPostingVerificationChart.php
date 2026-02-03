<?php

namespace App\Filament\Widgets;

use App\Models\JobPostings;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class JobPostingVerificationChart extends ChartWidget
{
    protected static ?string $heading = 'Trend Verifikasi Lowongan (30 Hari Terakhir)';

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    public ?string $filter = '30days';

    protected function getData(): array
    {
        $days = match ($this->filter) {
            '7days' => 7,
            '30days' => 30,
            '90days' => 90,
            default => 30,
        };

        $dates = collect();
        $approvedData = [];
        $rejectedData = [];
        $pendingData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->startOfDay();
            $dates->push($date->format('d M'));

            $approvedData[] = JobPostings::where('verification_status', 'Approved')
                ->whereDate('created_at', $date)
                ->count();

            $rejectedData[] = JobPostings::where('verification_status', 'Rejected')
                ->whereDate('created_at', $date)
                ->count();

            $pendingData[] = JobPostings::where('verification_status', 'Pending')
                ->whereDate('created_at', $date)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Disetujui',
                    'data' => $approvedData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'fill' => true,
                ],
                [
                    'label' => 'Ditolak',
                    'data' => $rejectedData,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'fill' => true,
                ],
                [
                    'label' => 'Pending',
                    'data' => $pendingData,
                    'backgroundColor' => 'rgba(251, 191, 36, 0.2)',
                    'borderColor' => 'rgb(251, 191, 36)',
                    'fill' => true,
                ],
            ],
            'labels' => $dates->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            '7days' => '7 Hari',
            '30days' => '30 Hari',
            '90days' => '90 Hari',
        ];
    }
}
