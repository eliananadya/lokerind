<?php

namespace App\Filament\Resources\JobPostingResource\Pages;

use App\Filament\Resources\JobPostingResource;
use App\Models\JobPostings;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListJobPostings extends ListRecords
{
    protected static string $resource = JobPostingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->badge(JobPostings::count()),

            'pending' => Tab::make('Menunggu Verifikasi')
                ->badge(JobPostings::where('verification_status', 'Pending')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn($query) => $query->where('verification_status', 'Pending')),

            'approved' => Tab::make('Disetujui')
                ->badge(JobPostings::where('verification_status', 'Approved')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn($query) => $query->where('verification_status', 'Approved')),

            'rejected' => Tab::make('Ditolak')
                ->badge(JobPostings::where('verification_status', 'Rejected')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn($query) => $query->where('verification_status', 'Rejected')),

            'open' => Tab::make('Lowongan Aktif')
                ->badge(JobPostings::where('status', 'Open')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'Open')),

            'closed' => Tab::make('Lowongan Tutup')
                ->badge(JobPostings::where('status', 'Closed')->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'Closed')),
        ];
    }
}
