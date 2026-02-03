<?php

namespace App\Filament\Resources\JobPostingResource\Pages;

use App\Filament\Resources\JobPostingResource;
use App\Models\JobPostings;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewJobPosting extends ViewRecord
{
    protected static string $resource = JobPostingResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Perusahaan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('company.name')
                                    ->label('Nama Perusahaan')
                                    ->icon('heroicon-m-building-office-2')
                                    ->weight('bold')
                                    ->size(TextEntry\TextEntrySize::Large),

                                TextEntry::make('industry.name')
                                    ->label('Industri')
                                    ->badge()
                                    ->color('info'),
                            ]),
                    ])
                    ->collapsible()
                    ->icon('heroicon-o-building-office'),

                Section::make('Detail Lowongan Kerja')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Judul Lowongan')
                            ->weight('bold')
                            ->size(TextEntry\TextEntrySize::Large)
                            ->columnSpanFull(),

                        TextEntry::make('description')
                            ->label('Deskripsi Pekerjaan')
                            ->markdown()
                            ->columnSpanFull(),

                        Grid::make(3)
                            ->schema([
                                TextEntry::make('salary')
                                    ->label('Gaji')
                                    ->money('IDR')
                                    ->icon('heroicon-m-banknotes'),

                                TextEntry::make('type_salary')
                                    ->label('Tipe Gaji')
                                    ->badge(),

                                TextEntry::make('slot')
                                    ->label('Jumlah Posisi')
                                    ->badge()
                                    ->color('success')
                                    ->icon('heroicon-m-users'),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextEntry::make('typeJobs.name')
                                    ->label('Tipe Pekerjaan')
                                    ->badge()
                                    ->color('gray'),

                                TextEntry::make('has_interview')
                                    ->label('Memerlukan Interview')
                                    ->badge()
                                    ->formatStateUsing(fn($state) => $state ? 'Ya' : 'Tidak')
                                    ->color(fn($state) => $state ? 'success' : 'gray')
                                    ->icon(fn($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle'),

                                TextEntry::make('gender')
                                    ->label('Jenis Kelamin')
                                    ->badge()
                                    ->formatStateUsing(fn($state) => match ($state) {
                                        'Male' => 'Laki-laki',
                                        'Female' => 'Perempuan',
                                        'Both' => 'Laki-laki & Perempuan',
                                        default => $state,
                                    }),
                            ]),
                    ])
                    ->collapsible()
                    ->icon('heroicon-o-briefcase'),

                Section::make('Persyaratan Kandidat')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('min_age')
                                    ->label('Usia Minimum')
                                    ->suffix(' tahun')
                                    ->icon('heroicon-m-calendar'),

                                TextEntry::make('max_age')
                                    ->label('Usia Maksimum')
                                    ->suffix(' tahun')
                                    ->icon('heroicon-m-calendar'),

                                TextEntry::make('age_range')
                                    ->label('Range Usia')
                                    ->state(fn($record) => "{$record->min_age} - {$record->max_age} tahun")
                                    ->badge()
                                    ->color('info'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('min_height')
                                    ->label('Tinggi Minimum')
                                    ->suffix(' cm')
                                    ->icon('heroicon-m-arrow-trending-up')
                                    ->placeholder('Tidak ada persyaratan'),

                                TextEntry::make('min_weight')
                                    ->label('Berat Minimum')
                                    ->suffix(' kg')
                                    ->icon('heroicon-m-scale')
                                    ->placeholder('Tidak ada persyaratan'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('level_english')
                                    ->label('Level Bahasa Inggris')
                                    ->badge()
                                    ->color('primary')
                                    ->formatStateUsing(fn($state) => ucfirst($state)),

                                TextEntry::make('level_mandarin')
                                    ->label('Level Bahasa Mandarin')
                                    ->badge()
                                    ->color('warning')
                                    ->formatStateUsing(fn($state) => ucfirst($state)),
                            ]),
                    ])
                    ->collapsible()
                    ->icon('heroicon-o-user-group'),

                Section::make('Lokasi & Jadwal Rekrutmen')
                    ->schema([
                        TextEntry::make('address')
                            ->label('Alamat Lengkap')
                            ->icon('heroicon-m-map-pin')
                            ->columnSpanFull(),

                        TextEntry::make('city.name')
                            ->label('Kota')
                            ->badge()
                            ->icon('heroicon-m-map-pin')
                            ->color('info'),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('open_recruitment')
                                    ->label('Buka Rekrutmen')
                                    ->date('d F Y')
                                    ->icon('heroicon-m-calendar-days')
                                    ->color('success'),

                                TextEntry::make('close_recruitment')
                                    ->label('Tutup Rekrutmen')
                                    ->date('d F Y')
                                    ->icon('heroicon-m-calendar-days')
                                    ->color('danger'),
                            ]),
                    ])
                    ->collapsible()
                    ->icon('heroicon-o-map-pin'),

                Section::make('Skill yang Dibutuhkan')
                    ->schema([
                        TextEntry::make('skills.name')
                            ->label('')
                            ->badge()
                            ->color('primary')
                            ->separator(',')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->icon('heroicon-o-academic-cap'),

                Section::make('Benefits yang Ditawarkan')
                    ->schema([
                        RepeatableEntry::make('benefits')
                            ->label('')
                            ->schema([
                                TextEntry::make('benefit.name')
                                    ->label('Benefit')
                                    ->icon('heroicon-m-gift')
                                    ->weight('bold'),

                                TextEntry::make('benefit_type')
                                    ->label('Tipe')
                                    ->badge()
                                    ->color('info'),

                                TextEntry::make('amount')
                                    ->label('Jumlah')
                                    ->money('IDR')
                                    ->weight('bold'),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->icon('heroicon-o-gift'),

                Section::make('Status & Verifikasi')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('verification_status')
                                    ->label('Status Verifikasi')
                                    ->badge()
                                    ->size(TextEntry\TextEntrySize::Large)
                                    ->color(fn(string $state): string => match ($state) {
                                        'Approved' => 'success',
                                        'Rejected' => 'danger',
                                        'Pending' => 'warning',
                                    })
                                    ->icon(fn(string $state): string => match ($state) {
                                        'Approved' => 'heroicon-o-check-circle',
                                        'Rejected' => 'heroicon-o-x-circle',
                                        'Pending' => 'heroicon-o-clock',
                                    })
                                    ->formatStateUsing(fn($state) => match ($state) {
                                        'Approved' => 'Disetujui',
                                        'Rejected' => 'Ditolak',
                                        'Pending' => 'Menunggu Verifikasi',
                                        default => $state,
                                    }),

                                TextEntry::make('status')
                                    ->label('Status Lowongan')
                                    ->badge()
                                    ->size(TextEntry\TextEntrySize::Large)
                                    ->color(fn(string $state): string => match ($state) {
                                        'Open' => 'success',
                                        'Closed' => 'danger',
                                        'Draft' => 'gray',
                                    })
                                    ->formatStateUsing(fn($state) => match ($state) {
                                        'Open' => 'Buka',
                                        'Closed' => 'Tutup',
                                        'Draft' => 'Draft',
                                        default => $state,
                                    }),

                                TextEntry::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->dateTime('d F Y, H:i')
                                    ->icon('heroicon-m-clock'),
                            ]),
                    ])
                    ->collapsible()
                    ->icon('heroicon-o-clipboard-document-check'),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Setujui Lowongan')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Setujui Lowongan Kerja')
                ->modalDescription(fn() => "Apakah Anda yakin ingin menyetujui lowongan '{$this->record->title}' dari {$this->record->company?->name}?")
                ->modalSubmitActionLabel('Ya, Setujui')
                ->action(function () {
                    $this->record->update([
                        'verification_status' => 'Approved',
                        'status' => 'Open',
                    ]);

                    Notification::make()
                        ->title('Lowongan Disetujui')
                        ->body("Lowongan '{$this->record->title}' berhasil disetujui dan sekarang berstatus Buka.")
                        ->success()
                        ->send();

                    return redirect()->route('filament.admin.resources.job-postings.index');
                })
                ->visible(fn() => $this->record->verification_status === 'Pending'),

            Actions\Action::make('reject')
                ->label('Tolak Lowongan')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Tolak Lowongan Kerja')
                ->modalDescription(fn() => "Apakah Anda yakin ingin menolak lowongan '{$this->record->title}' dari {$this->record->company?->name}?")
                ->modalSubmitActionLabel('Ya, Tolak')
                ->form([
                    Forms\Components\Textarea::make('rejection_reason')
                        ->label('Alasan Penolakan')
                        ->required()
                        ->rows(4)
                        ->placeholder('Jelaskan alasan penolakan lowongan ini...'),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'verification_status' => 'Rejected',
                        'status' => 'Draft',
                    ]);

                    Notification::make()
                        ->title('Lowongan Ditolak')
                        ->body("Lowongan '{$this->record->title}' telah ditolak.")
                        ->danger()
                        ->send();

                    // TODO: Kirim notifikasi ke company dengan alasan: $data['rejection_reason']

                    return redirect()->route('filament.admin.resources.job-postings.index');
                })
                ->visible(fn() => $this->record->verification_status === 'Pending'),

            Actions\Action::make('review')
                ->label('Review Ulang')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Review Ulang Lowongan')
                ->modalDescription('Kembalikan status lowongan ini ke Pending untuk direview ulang?')
                ->modalSubmitActionLabel('Ya, Review Ulang')
                ->action(function () {
                    $this->record->update([
                        'verification_status' => 'Pending',
                    ]);

                    Notification::make()
                        ->title('Status Diubah')
                        ->body('Lowongan dikembalikan ke status Pending untuk direview ulang.')
                        ->warning()
                        ->send();
                })
                ->visible(fn() => in_array($this->record->verification_status, ['Approved', 'Rejected'])),

            Actions\DeleteAction::make()
                ->label('Hapus Lowongan')
                ->visible(fn() => $this->record->verification_status === 'Rejected'),

            Actions\Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn() => JobPostingResource::getUrl('index')),
        ];
    }
}
