<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Filament\Resources\ApplicationResource\RelationManagers;
use App\Models\Applications;
use App\Models\Candidates;
use App\Models\JobPostings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use Filament\Support\Colors\Color;

class ApplicationResource extends Resource
{
    protected static ?string $model = Applications::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Lamaran Masuk';

    protected static ?string $modelLabel = 'Lamaran';

    protected static ?string $pluralModelLabel = 'Lamaran Masuk';

    protected static ?string $navigationGroup = 'Manajemen Lowongan';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Lamaran')
                    ->description('Detail lamaran pekerjaan')
                    ->schema([
                        Forms\Components\Select::make('job_posting_id')
                            ->label('Lowongan Pekerjaan')
                            ->relationship('jobPosting', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('candidates_id')
                            ->label('Kandidat')
                            ->relationship('candidate', 'id')
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->user->name ?? 'N/A')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('status')
                            ->label('Status Lamaran')
                            ->options([
                                'pending' => 'Pending',
                                'reviewed' => 'Ditinjau',
                                'approved' => 'Diterima',
                                'rejected' => 'Ditolak',
                                'withdrawn' => 'Dibatalkan',
                            ])
                            ->required()
                            ->default('pending')
                            ->native(false)
                            ->columnSpan(1),

                        Forms\Components\DatePicker::make('applied_at')
                            ->label('Tanggal Melamar')
                            ->required()
                            ->default(now())
                            ->native(false)
                            ->columnSpan(1),

                        Forms\Components\Textarea::make('message')
                            ->label('Pesan dari Kandidat')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('invited_by_company')
                            ->label('Diundang oleh Perusahaan')
                            ->default(false)
                            ->columnSpan(1),

                        Forms\Components\DateTimePicker::make('invited_at')
                            ->label('Tanggal Diundang')
                            ->native(false)
                            ->columnSpan(1)
                            ->visible(fn(Forms\Get $get) => $get('invited_by_company')),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Feedback & Rating')
                    ->description('Penilaian dari kedua belah pihak')
                    ->schema([
                        Forms\Components\TextInput::make('rating_candidates')
                            ->label('Rating dari Kandidat (1-5)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->suffix('⭐')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('rating_company')
                            ->label('Rating dari Perusahaan (1-5)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->suffix('⭐')
                            ->columnSpan(1),

                        Forms\Components\Textarea::make('review_candidate')
                            ->label('Review dari Kandidat')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('review_company')
                            ->label('Review dari Perusahaan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsed(),

                Forms\Components\Section::make('Informasi Pembatalan')
                    ->description('Data jika lamaran dibatalkan')
                    ->schema([
                        Forms\Components\DateTimePicker::make('withdrawn_at')
                            ->label('Tanggal Dibatalkan')
                            ->native(false)
                            ->columnSpan(1),

                        Forms\Components\Textarea::make('withdraw_reason')
                            ->label('Alasan Pembatalan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->visible(fn(Forms\Get $get) => $get('status') === 'withdrawn'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null) // Nonaktifkan klik pada row
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\ImageColumn::make('candidate.user.photo')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png'))
                    ->toggleable(),

                Tables\Columns\TextColumn::make('candidate.user.name')
                    ->label('Nama Kandidat')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(Applications $record): string => $record->candidate->user->email ?? '-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('jobPosting.title')
                    ->label('Posisi yang Dilamar')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    })
                    ->description(
                        fn(Applications $record): string =>
                        $record->jobPosting->companies->name ?? 'Perusahaan tidak ditemukan'
                    )
                    ->toggleable(),

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
                    ->icon(fn(string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'reviewed' => 'heroicon-o-eye',
                        'approved' => 'heroicon-o-check-circle',
                        'rejected' => 'heroicon-o-x-circle',
                        'withdrawn' => 'heroicon-o-no-symbol',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'reviewed' => 'Ditinjau',
                        'approved' => 'Diterima',
                        'rejected' => 'Ditolak',
                        'withdrawn' => 'Dibatalkan',
                        default => $state,
                    })
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('invited_by_company')
                    ->label('Undangan')
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope')
                    ->falseIcon('heroicon-o-user')
                    ->trueColor('info')
                    ->falseColor('gray')
                    ->tooltip(
                        fn(Applications $record): string =>
                        $record->invited_by_company ? 'Diundang Perusahaan' : 'Melamar Sendiri'
                    )
                    ->toggleable(),

                Tables\Columns\TextColumn::make('applied_at')
                    ->label('Tanggal Melamar')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('rating_company')
                    ->label('Rating')
                    ->formatStateUsing(
                        fn(?int $state): string =>
                        $state ? str_repeat('⭐', $state) : '-'
                    )
                    ->tooltip(
                        fn(?int $state): string =>
                        $state ? "Rating: {$state}/5" : 'Belum ada rating'
                    )
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'reviewed' => 'Ditinjau',
                        'approved' => 'Diterima',
                        'rejected' => 'Ditolak',
                        'withdrawn' => 'Dibatalkan',
                    ])
                    ->multiple()
                    ->placeholder('Semua Status'),

                Tables\Filters\TernaryFilter::make('invited_by_company')
                    ->label('Undangan Perusahaan')
                    ->placeholder('Semua')
                    ->trueLabel('Diundang')
                    ->falseLabel('Melamar Sendiri'),

                Tables\Filters\Filter::make('applied_at')
                    ->form([
                        Forms\Components\DatePicker::make('applied_from')
                            ->label('Dari Tanggal')
                            ->native(false),
                        Forms\Components\DatePicker::make('applied_until')
                            ->label('Sampai Tanggal')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['applied_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('applied_at', '>=', $date),
                            )
                            ->when(
                                $data['applied_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('applied_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['applied_from'] ?? null) {
                            $indicators[] = 'Dari: ' . \Carbon\Carbon::parse($data['applied_from'])->format('d M Y');
                        }
                        if ($data['applied_until'] ?? null) {
                            $indicators[] = 'Sampai: ' . \Carbon\Carbon::parse($data['applied_until'])->format('d M Y');
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('info'),

                    Tables\Actions\Action::make('approve')
                        ->label('Terima')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Terima Lamaran')
                        ->modalDescription(
                            fn(Applications $record) =>
                            'Anda yakin ingin menerima lamaran dari ' . ($record->candidate->user->name ?? 'kandidat ini') . '?'
                        )
                        ->modalSubmitActionLabel('Ya, Terima')
                        ->action(function (Applications $record) {
                            $record->update([
                                'status' => 'approved',
                            ]);

                            Notification::make()
                                ->title('Lamaran Diterima')
                                ->body('Lamaran dari ' . ($record->candidate->user->name ?? 'kandidat') . ' berhasil diterima.')
                                ->success()
                                ->send();
                        })
                        ->visible(
                            fn(Applications $record): bool =>
                            in_array($record->status, ['pending', 'reviewed'])
                        ),

                    Tables\Actions\Action::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Tolak Lamaran')
                        ->modalDescription(
                            fn(Applications $record) =>
                            'Anda yakin ingin menolak lamaran dari ' . ($record->candidate->user->name ?? 'kandidat ini') . '?'
                        )
                        ->form([
                            Forms\Components\Textarea::make('rejection_reason')
                                ->label('Alasan Penolakan (Opsional)')
                                ->placeholder('Berikan alasan penolakan untuk kandidat...')
                                ->rows(3),
                        ])
                        ->modalSubmitActionLabel('Ya, Tolak')
                        ->action(function (Applications $record, array $data) {
                            $record->update([
                                'status' => 'rejected',
                                'withdraw_reason' => $data['rejection_reason'] ?? null,
                            ]);

                            Notification::make()
                                ->title('Lamaran Ditolak')
                                ->body('Lamaran dari ' . ($record->candidate->user->name ?? 'kandidat') . ' telah ditolak.')
                                ->warning()
                                ->send();
                        })
                        ->visible(
                            fn(Applications $record): bool =>
                            in_array($record->status, ['pending', 'reviewed'])
                        ),

                    Tables\Actions\Action::make('review')
                        ->label('Tinjau')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->action(function (Applications $record) {
                            $record->update([
                                'status' => 'reviewed',
                            ]);

                            Notification::make()
                                ->title('Status Diperbarui')
                                ->body('Lamaran ditandai sebagai sedang ditinjau.')
                                ->info()
                                ->send();
                        })
                        ->visible(
                            fn(Applications $record): bool =>
                            $record->status === 'pending'
                        ),

                    Tables\Actions\DeleteAction::make(),
                ])
                    ->label('Aksi')
                    ->color('primary')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size('sm')
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve_bulk')
                        ->label('Terima Semua')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $count = 0;
                            foreach ($records as $record) {
                                if (in_array($record->status, ['pending', 'reviewed'])) {
                                    $record->update(['status' => 'approved']);
                                    $count++;
                                }
                            }

                            Notification::make()
                                ->title('Lamaran Diterima')
                                ->body("{$count} lamaran berhasil diterima.")
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\BulkAction::make('reject_bulk')
                        ->label('Tolak Semua')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $count = 0;
                            foreach ($records as $record) {
                                if (in_array($record->status, ['pending', 'reviewed'])) {
                                    $record->update(['status' => 'rejected']);
                                    $count++;
                                }
                            }

                            Notification::make()
                                ->title('Lamaran Ditolak')
                                ->body("{$count} lamaran telah ditolak.")
                                ->warning()
                                ->send();
                        }),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Lamaran')
            ->emptyStateDescription('Belum ada lamaran yang masuk untuk lowongan pekerjaan.')
            ->emptyStateIcon('heroicon-o-document-text');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplications::route('/'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
            'view' => Pages\ViewApplication::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() > 0 ? 'warning' : 'primary';
    }
}
