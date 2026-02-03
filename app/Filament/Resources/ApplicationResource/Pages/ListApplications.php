<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use App\Models\Applications;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListApplications extends ListRecords
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Create button dihapus - lamaran dibuat dari frontend
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->badge(Applications::count())
                ->badgeColor('primary'),

            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'pending'))
                ->badge(Applications::where('status', 'pending')->count())
                ->badgeColor('warning')
                ->icon('heroicon-o-clock'),

            'reviewed' => Tab::make('Ditinjau')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'reviewed'))
                ->badge(Applications::where('status', 'reviewed')->count())
                ->badgeColor('info')
                ->icon('heroicon-o-eye'),

            'approved' => Tab::make('Diterima')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'approved'))
                ->badge(Applications::where('status', 'approved')->count())
                ->badgeColor('success')
                ->icon('heroicon-o-check-circle'),

            'rejected' => Tab::make('Ditolak')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'rejected'))
                ->badge(Applications::where('status', 'rejected')->count())
                ->badgeColor('danger')
                ->icon('heroicon-o-x-circle'),

            'withdrawn' => Tab::make('Dibatalkan')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'withdrawn'))
                ->badge(Applications::where('status', 'withdrawn')->count())
                ->badgeColor('gray')
                ->icon('heroicon-o-no-symbol'),

            'invited' => Tab::make('Diundang')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('invited_by_company', true))
                ->badge(Applications::where('invited_by_company', true)->count())
                ->badgeColor('purple')
                ->icon('heroicon-o-envelope'),
        ];
    }
}
