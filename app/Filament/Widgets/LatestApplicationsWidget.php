<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ApplicationResource;
use App\Models\Applications;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestApplicationsWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Lamaran Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Applications::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('candidate.user.photo')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png')),

                Tables\Columns\TextColumn::make('candidate.user.name')
                    ->label('Kandidat')
                    ->searchable()
                    ->weight('bold')
                    ->description(
                        fn(Applications $record): string =>
                        $record->jobPosting->title ?? '-'
                    ),

                Tables\Columns\TextColumn::make('jobPosting.companies.name')
                    ->label('Perusahaan')
                    ->searchable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'reviewed' => 'info',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'withdrawn' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'reviewed' => 'Ditinjau',
                        'approved' => 'Diterima',
                        'rejected' => 'Ditolak',
                        'withdrawn' => 'Dibatalkan',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('applied_at')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn(Applications $record): string => ApplicationResource::getUrl('view', ['record' => $record]))
                    ->color('info'),
            ]);
    }
}
