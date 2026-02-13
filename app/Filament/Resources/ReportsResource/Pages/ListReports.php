<?php

namespace App\Filament\Resources\ReportsResource\Pages;

use App\Filament\Resources\ReportsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReports extends ListRecords
{
    protected static string $resource = ReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tidak ada create action
        ];
    }

    // Tambahkan ini untuk reset default filter
    protected function getTableFiltersFormColumns(): int
    {
        return 3; // Opsional: atur jumlah kolom filter
    }

    // PENTING: Hapus filter default
    public function getTableFilters(): array
    {
        return []; // Kosongkan supaya tidak ada default filter
    }
}
