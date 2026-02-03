<?php

namespace App\Filament\Resources;

use App\Models\JobPostings;
use App\Models\Industries;
use App\Models\Companies;
use App\Models\Skills;
use App\Models\Benefits;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use App\Models\JobDates;
use Filament\Tables\Table;
use App\Filament\Resources\JobPostingResource\Pages;
use App\Models\JobPostingBenefit;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Filters\SelectFilter;

class JobPostingResource extends Resource
{
    protected static ?string $model = JobPostings::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Verifikasi Lowongan';

    protected static ?string $modelLabel = 'Lowongan Kerja';

    protected static ?string $pluralModelLabel = 'Verifikasi Lowongan Kerja';

    protected static ?string $navigationGroup = 'Manajemen Lowongan';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('verification_status', 'Pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Perusahaan')
                    ->schema([
                        Forms\Components\TextInput::make('company.name')
                            ->label('Nama Perusahaan')
                            ->disabled()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('industry.name')
                            ->label('Industri')
                            ->disabled()
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('Detail Lowongan')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Lowongan')
                            ->disabled()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Pekerjaan')
                            ->disabled()
                            ->rows(5)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('salary')
                            ->label('Gaji')
                            ->disabled()
                            ->numeric()
                            ->prefix('Rp')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('type_salary')
                            ->label('Tipe Gaji')
                            ->disabled()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('slot')
                            ->label('Jumlah Posisi')
                            ->disabled()
                            ->numeric()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('typeJobs.name')
                            ->label('Tipe Pekerjaan')
                            ->disabled()
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('Persyaratan Kandidat')
                    ->schema([
                        Forms\Components\TextInput::make('gender')
                            ->label('Jenis Kelamin')
                            ->disabled()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('min_age')
                            ->label('Usia Minimum')
                            ->disabled()
                            ->numeric()
                            ->suffix('tahun')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('max_age')
                            ->label('Usia Maksimum')
                            ->disabled()
                            ->numeric()
                            ->suffix('tahun')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('min_height')
                            ->label('Tinggi Minimum')
                            ->disabled()
                            ->numeric()
                            ->suffix('cm')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('min_weight')
                            ->label('Berat Minimum')
                            ->disabled()
                            ->numeric()
                            ->suffix('kg')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('level_english')
                            ->label('Level Bahasa Inggris')
                            ->disabled()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('level_mandarin')
                            ->label('Level Bahasa Mandarin')
                            ->disabled()
                            ->columnSpan(1),

                        Forms\Components\Placeholder::make('has_interview')
                            ->label('Memerlukan Interview')
                            ->content(fn($record) => $record?->has_interview ? 'Ya' : 'Tidak')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('Lokasi & Jadwal')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat Lengkap')
                            ->disabled()
                            ->rows(2)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('city.name')
                            ->label('Kota')
                            ->disabled()
                            ->columnSpan(1),

                        Forms\Components\DatePicker::make('open_recruitment')
                            ->label('Tanggal Buka Rekrutmen')
                            ->disabled()
                            ->columnSpan(1),

                        Forms\Components\DatePicker::make('close_recruitment')
                            ->label('Tanggal Tutup Rekrutmen')
                            ->disabled()
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('Skill & Benefit')
                    ->schema([
                        Forms\Components\TagsInput::make('skills')
                            ->label('Skill yang Dibutuhkan')
                            ->disabled()
                            ->columnSpanFull()
                            ->default(fn($record) => $record?->skills->pluck('name')->toArray()),

                        Forms\Components\Repeater::make('benefits')
                            ->label('Benefits yang Ditawarkan')
                            ->schema([
                                Forms\Components\TextInput::make('benefit.name')
                                    ->label('Benefit')
                                    ->disabled(),

                                Forms\Components\TextInput::make('benefit_type')
                                    ->label('Tipe')
                                    ->disabled(),

                                Forms\Components\TextInput::make('amount')
                                    ->label('Jumlah')
                                    ->disabled()
                                    ->numeric(),
                            ])
                            ->disabled()
                            ->columnSpanFull()
                            ->columns(3),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Status Verifikasi')
                    ->schema([
                        Forms\Components\Select::make('verification_status')
                            ->label('Status Verifikasi')
                            ->options([
                                'Pending' => 'Pending',
                                'Approved' => 'Disetujui',
                                'Rejected' => 'Ditolak',
                            ])
                            ->required()
                            ->reactive()
                            ->columnSpan(1),

                        Forms\Components\Select::make('status')
                            ->label('Status Lowongan')
                            ->options([
                                'Draft' => 'Draft',
                                'Open' => 'Buka',
                                'Closed' => 'Tutup',
                            ])
                            ->required()
                            ->columnSpan(1),

                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->visible(fn(callable $get) => $get('verification_status') === 'Rejected')
                            ->required(fn(callable $get) => $get('verification_status') === 'Rejected')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Jelaskan alasan penolakan lowongan ini...'),
                    ])
                    ->columns(2)
                    ->visible(fn($livewire) => $livewire instanceof Pages\ViewJobPosting),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->size(Tables\Columns\TextColumn\TextColumnSize::ExtraSmall),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Lowongan')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->description(fn(JobPostings $record) => $record->company?->name)
                    ->wrap()
                    ->limit(50),

                Tables\Columns\TextColumn::make('company.name')
                    ->label('Perusahaan')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->icon('heroicon-m-building-office-2'),

                Tables\Columns\TextColumn::make('industry.name')
                    ->label('Industri')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('typeJobs.name')
                    ->label('Tipe Pekerjaan')
                    ->sortable()
                    ->toggleable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('salary')
                    ->label('Gaji')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('slot')
                    ->label('Slot')
                    ->sortable()
                    ->alignCenter()
                    ->toggleable()
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('has_interview')
                    ->label('Interview')
                    ->boolean()
                    ->alignCenter()
                    ->toggleable()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('verification_status')
                    ->label('Status Verifikasi')
                    ->badge()
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
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status Lowongan')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Open' => 'success',
                        'Closed' => 'danger',
                        'Draft' => 'gray',
                    })
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('city.name')
                    ->label('Kota')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->icon('heroicon-m-map-pin'),

                Tables\Columns\TextColumn::make('open_recruitment')
                    ->label('Buka Rekrutmen')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('close_recruitment')
                    ->label('Tutup Rekrutmen')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('verification_status')
                    ->label('Status Verifikasi')
                    ->options([
                        'Pending' => 'Pending',
                        'Approved' => 'Disetujui',
                        'Rejected' => 'Ditolak',
                    ])
                    ->default('Pending'),

                SelectFilter::make('status')
                    ->label('Status Lowongan')
                    ->options([
                        'Draft' => 'Draft',
                        'Open' => 'Buka',
                        'Closed' => 'Tutup',
                    ]),

                SelectFilter::make('industries_id')
                    ->label('Industri')
                    ->relationship('industry', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('type_jobs_id')
                    ->label('Tipe Pekerjaan')
                    ->relationship('typeJobs', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detail')
                    ->icon('heroicon-o-eye'),

                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Lowongan Kerja')
                    ->modalDescription(fn(JobPostings $record) => "Apakah Anda yakin ingin menyetujui lowongan '{$record->title}' dari {$record->company?->name}?")
                    ->modalSubmitActionLabel('Ya, Setujui')
                    ->action(function (JobPostings $record) {
                        $record->update([
                            'verification_status' => 'Approved',
                            'status' => 'Open',
                        ]);

                        Notification::make()
                            ->title('Lowongan Disetujui')
                            ->body("Lowongan '{$record->title}' berhasil disetujui.")
                            ->success()
                            ->send();
                    })
                    ->visible(fn(JobPostings $record) => $record->verification_status === 'Pending'),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Lowongan Kerja')
                    ->modalDescription(fn(JobPostings $record) => "Apakah Anda yakin ingin menolak lowongan '{$record->title}' dari {$record->company?->name}?")
                    ->modalSubmitActionLabel('Ya, Tolak')
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->rows(4)
                            ->placeholder('Jelaskan alasan penolakan lowongan ini...'),
                    ])
                    ->action(function (JobPostings $record, array $data) {
                        $record->update([
                            'verification_status' => 'Rejected',
                            'status' => 'Draft',
                        ]);

                        Notification::make()
                            ->title('Lowongan Ditolak')
                            ->body("Lowongan '{$record->title}' telah ditolak.")
                            ->danger()
                            ->send();

                        // TODO: Kirim notifikasi ke company
                    })
                    ->visible(fn(JobPostings $record) => $record->verification_status === 'Pending'),

                Tables\Actions\Action::make('review')
                    ->label('Review Ulang')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Review Ulang Lowongan')
                    ->modalDescription('Kembalikan status lowongan ini ke Pending untuk direview ulang?')
                    ->modalSubmitActionLabel('Ya, Review Ulang')
                    ->action(function (JobPostings $record) {
                        $record->update([
                            'verification_status' => 'Pending',
                        ]);

                        Notification::make()
                            ->title('Status Diubah')
                            ->body('Lowongan dikembalikan ke status Pending.')
                            ->warning()
                            ->send();
                    })
                    ->visible(fn(JobPostings $record) => in_array($record->verification_status, ['Approved', 'Rejected'])),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->visible(fn(JobPostings $record) => $record->verification_status === 'Rejected'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve_bulk')
                        ->label('Setujui Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Setujui Lowongan Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menyetujui semua lowongan yang dipilih?')
                        ->modalSubmitActionLabel('Ya, Setujui Semua')
                        ->action(function ($records) {
                            $count = 0;
                            foreach ($records as $record) {
                                if ($record->verification_status === 'Pending') {
                                    $record->update([
                                        'verification_status' => 'Approved',
                                        'status' => 'Open',
                                    ]);
                                    $count++;
                                }
                            }

                            Notification::make()
                                ->title('Berhasil Disetujui')
                                ->body("{$count} lowongan berhasil disetujui.")
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\BulkAction::make('reject_bulk')
                        ->label('Tolak Terpilih')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Tolak Lowongan Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menolak semua lowongan yang dipilih?')
                        ->modalSubmitActionLabel('Ya, Tolak Semua')
                        ->form([
                            Forms\Components\Textarea::make('rejection_reason')
                                ->label('Alasan Penolakan')
                                ->required()
                                ->rows(4)
                                ->placeholder('Jelaskan alasan penolakan...'),
                        ])
                        ->action(function ($records, array $data) {
                            $count = 0;
                            foreach ($records as $record) {
                                if ($record->verification_status === 'Pending') {
                                    $record->update([
                                        'verification_status' => 'Rejected',
                                        'status' => 'Draft',
                                    ]);
                                    $count++;
                                }
                            }

                            Notification::make()
                                ->title('Berhasil Ditolak')
                                ->body("{$count} lowongan telah ditolak.")
                                ->danger()
                                ->send();
                        }),

                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobPostings::route('/'),
            'view' => Pages\ViewJobPosting::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
