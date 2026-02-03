<?php

namespace App\Filament\Widgets;

use App\Models\Applications;
use Filament\Widgets\ChartWidget;

class ApplicationsChart extends ChartWidget
{
    protected static ?string $heading = 'Trend Lamaran Masuk';

    protected static ?int $sort = 3;

    protected static ?string $maxHeight = '300px';

    public ?string $filter = '7days';

    protected function getData(): array
    {
        $data = $this->getApplicationData();

        return [
            'datasets' => [
                [
                    'label' => 'Lamaran Masuk',
                    'data' => $data['values'],
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            '7days' => '7 Hari Terakhir',
            '30days' => '30 Hari Terakhir',
            '3months' => '3 Bulan Terakhir',
            'year' => 'Tahun Ini',
        ];
    }

    protected function getApplicationData(): array
    {
        $filter = $this->filter;
        $labels = [];
        $values = [];

        switch ($filter) {
            case '7days':
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $labels[] = $date->format('d M');
                    $values[] = Applications::whereDate('applied_at', $date)->count();
                }
                break;

            case '30days':
                for ($i = 29; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $labels[] = $date->format('d M');
                    $values[] = Applications::whereDate('applied_at', $date)->count();
                }
                break;

            case '3months':
                for ($i = 11; $i >= 0; $i--) {
                    $date = now()->subWeeks($i);
                    $startOfWeek = $date->startOfWeek();
                    $endOfWeek = $date->copy()->endOfWeek();
                    $labels[] = $startOfWeek->format('d M');
                    $values[] = Applications::whereBetween('applied_at', [
                        $startOfWeek,
                        $endOfWeek
                    ])->count();
                }
                break;

            case 'year':
                for ($i = 11; $i >= 0; $i--) {
                    $date = now()->subMonths($i);
                    $labels[] = $date->format('M Y');
                    $values[] = Applications::whereMonth('applied_at', $date->month)
                        ->whereYear('applied_at', $date->year)
                        ->count();
                }
                break;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }
}
