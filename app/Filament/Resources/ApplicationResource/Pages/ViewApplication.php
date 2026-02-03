<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Colors\Color;
use Filament\Notifications\Notification;

class ViewApplication extends ViewRecord
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Terima Lamaran')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Terima Lamaran')
                ->modalDescription('Anda yakin ingin menerima lamaran ini?')
                ->modalSubmitActionLabel('Ya, Terima')
                ->action(function () {
                    $this->record->update([
                        'status' => 'approved',
                    ]);

                    Notification::make()
                        ->title('Lamaran Diterima')
                        ->body('Lamaran berhasil diterima.')
                        ->success()
                        ->send();

                    return redirect()->to(ApplicationResource::getUrl('index'));
                })
                ->visible(fn(): bool => in_array($this->record->status, ['pending', 'reviewed'])),

            Actions\Action::make('reject')
                ->label('Tolak Lamaran')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Tolak Lamaran')
                ->modalDescription('Anda yakin ingin menolak lamaran ini?')
                ->form([
                    \Filament\Forms\Components\Textarea::make('rejection_reason')
                        ->label('Alasan Penolakan (Opsional)')
                        ->placeholder('Berikan alasan penolakan untuk kandidat...')
                        ->rows(3),
                ])
                ->modalSubmitActionLabel('Ya, Tolak')
                ->action(function (array $data) {
                    $this->record->update([
                        'status' => 'rejected',
                        'withdraw_reason' => $data['rejection_reason'] ?? null,
                    ]);

                    Notification::make()
                        ->title('Lamaran Ditolak')
                        ->body('Lamaran telah ditolak.')
                        ->warning()
                        ->send();

                    return redirect()->to(ApplicationResource::getUrl('index'));
                })
                ->visible(fn(): bool => in_array($this->record->status, ['pending', 'reviewed'])),

            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Kandidat')
                    ->schema([
                        Infolists\Components\Split::make([
                            Infolists\Components\ImageEntry::make('candidate.user.photo')
                                ->label('Foto Profil')
                                ->circular()
                                ->defaultImageUrl(url('/images/default-avatar.png'))
                                ->size(100)
                                ->grow(false),

                            Infolists\Components\Grid::make(2)
                                ->schema([
                                    Infolists\Components\TextEntry::make('candidate.user.name')
                                        ->label('Nama Lengkap')
                                        ->weight('bold')
                                        ->size('lg'),

                                    Infolists\Components\TextEntry::make('candidate.user.email')
                                        ->label('Email')
                                        ->icon('heroicon-m-envelope')
                                        ->copyable(),

                                    Infolists\Components\TextEntry::make('candidate.phone')
                                        ->label('Nomor Telepon')
                                        ->icon('heroicon-m-phone')
                                        ->default('-'),

                                    Infolists\Components\TextEntry::make('candidate.education')
                                        ->label('Pendidikan')
                                        ->icon('heroicon-m-academic-cap')
                                        ->default('-'),
                                ])
                        ]),
                    ])
                    ->columnSpanFull(),

                Infolists\Components\Section::make('Detail Lowongan')
                    ->schema([
                        Infolists\Components\TextEntry::make('jobPosting.title')
                            ->label('Posisi yang Dilamar')
                            ->weight('bold')
                            ->size('lg')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('jobPosting.companies.name')
                            ->label('Perusahaan')
                            ->icon('heroicon-m-building-office'),

                        Infolists\Components\TextEntry::make('jobPosting.location')
                            ->label('Lokasi')
                            ->icon('heroicon-m-map-pin')
                            ->default('-'),

                        Infolists\Components\TextEntry::make('jobPosting.salary')
                            ->label('Gaji')
                            ->money('IDR')
                            ->default('-'),

                        Infolists\Components\TextEntry::make('jobPosting.type')
                            ->label('Tipe Pekerjaan')
                            ->badge()
                            ->default('-'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Status Lamaran')
                    ->schema([
                        Infolists\Components\TextEntry::make('status')
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
                            })
                            ->icon(fn(string $state): string => match ($state) {
                                'pending' => 'heroicon-o-clock',
                                'reviewed' => 'heroicon-o-eye',
                                'approved' => 'heroicon-o-check-circle',
                                'rejected' => 'heroicon-o-x-circle',
                                'withdrawn' => 'heroicon-o-no-symbol',
                                default => 'heroicon-o-question-mark-circle',
                            })
                            ->size('lg'),

                        Infolists\Components\TextEntry::make('invited_by_company')
                            ->label('Jenis Lamaran')
                            ->badge()
                            ->color(fn($state) => $state ? 'info' : 'gray')
                            ->icon(fn($state) => $state ? 'heroicon-o-envelope' : 'heroicon-o-user')
                            ->formatStateUsing(
                                fn($state): string =>
                                $state ? 'Diundang Perusahaan' : 'Melamar Sendiri'
                            ),

                        Infolists\Components\TextEntry::make('applied_at')
                            ->label('Tanggal Melamar')
                            ->date('d F Y')
                            ->icon('heroicon-m-calendar'),

                        Infolists\Components\TextEntry::make('invited_at')
                            ->label('Tanggal Diundang')
                            ->dateTime('d F Y H:i')
                            ->icon('heroicon-m-calendar')
                            ->visible(fn($record) => $record->invited_by_company && $record->invited_at),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Pesan dari Kandidat')
                    ->schema([
                        Infolists\Components\TextEntry::make('message')
                            ->label('')
                            ->placeholder('Tidak ada pesan')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn($record) => !empty($record->message))
                    ->collapsed(),

                Infolists\Components\Section::make('Rating & Review')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('rating_company')
                                    ->label('Rating dari Perusahaan')
                                    ->formatStateUsing(
                                        fn(?int $state): string =>
                                        $state ? str_repeat('⭐', $state) . " ({$state}/5)" : 'Belum ada rating'
                                    ),

                                Infolists\Components\TextEntry::make('rating_candidates')
                                    ->label('Rating dari Kandidat')
                                    ->formatStateUsing(
                                        fn(?int $state): string =>
                                        $state ? str_repeat('⭐', $state) . " ({$state}/5)" : 'Belum ada rating'
                                    ),

                                Infolists\Components\TextEntry::make('review_company')
                                    ->label('Review dari Perusahaan')
                                    ->placeholder('Belum ada review')
                                    ->columnSpanFull(),

                                Infolists\Components\TextEntry::make('review_candidate')
                                    ->label('Review dari Kandidat')
                                    ->placeholder('Belum ada review')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->collapsed()
                    ->visible(
                        fn($record) =>
                        $record->rating_company ||
                            $record->rating_candidates ||
                            $record->review_company ||
                            $record->review_candidate
                    ),

                Infolists\Components\Section::make('Informasi Pembatalan')
                    ->schema([
                        Infolists\Components\TextEntry::make('withdrawn_at')
                            ->label('Tanggal Dibatalkan')
                            ->dateTime('d F Y H:i')
                            ->icon('heroicon-m-calendar'),

                        Infolists\Components\TextEntry::make('withdraw_reason')
                            ->label('Alasan Pembatalan')
                            ->placeholder('Tidak ada alasan')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn($record) => $record->status === 'withdrawn' || $record->withdrawn_at)
                    ->collapsed(),
            ]);
    }
}
