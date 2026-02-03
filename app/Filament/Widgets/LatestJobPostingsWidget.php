<?php

namespace App\Filament\Widgets;

use App\Models\JobPostings;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Support\Enums\FontWeight;

class LatestJobPostingsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                JobPostings::query()
                    ->where('verification_status', 'Pending')
                    ->latest()
                    ->limit(5)
            )
            ->heading('Lowongan Terbaru - Menunggu Verifikasi')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->size(Tables\Columns\TextColumn\TextColumnSize::ExtraSmall),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Lowongan')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->description(fn(JobPostings $record) => $record->company?->name)
                    ->wrap()
                    ->limit(40),

                Tables\Columns\TextColumn::make('company.name')
                    ->label('Perusahaan')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-building-office-2')
                    ->limit(30),

                Tables\Columns\TextColumn::make('salary')
                    ->label('Gaji')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('slot')
                    ->label('Slot')
                    ->badge()
                    ->color('success')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('verification_status')
                    ->label('Status')
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-o-clock'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->url(fn(JobPostings $record) => route('filament.admin.resources.job-postings.view', ['record' => $record->id])),

                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (JobPostings $record) {
                        $record->update([
                            'verification_status' => 'Approved',
                            'status' => 'Open',
                        ]);
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (JobPostings $record) {
                        $record->update([
                            'verification_status' => 'Rejected',
                            'status' => 'Draft',
                        ]);
                    }),
            ]);
    }
}
