<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportsResource\Pages;
use App\Models\Reports;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class ReportsResource extends Resource
{
    protected static ?string $model = Reports::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $navigationLabel = 'Laporan';

    protected static ?string $modelLabel = 'Laporan';

    protected static ?string $pluralModelLabel = 'Laporan';

    protected static ?string $navigationGroup = 'Manajemen';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Laporan')
                    ->schema([
                        Forms\Components\Textarea::make('reason')
                            ->label('Alasan Laporan')
                            ->required()
                            ->disabled()
                            ->rows(3),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved (Hapus Rating & Review)',
                                'rejected' => 'Rejected (Tetap Tampilkan Rating & Review)',
                            ])
                            ->required()
                            ->default('pending')
                            ->native(false)
                            ->helperText('Approved = Rating & Review akan dihapus. Rejected = Rating & Review tetap ada.'),

                        Forms\Components\Select::make('application_id')
                            ->label('Application')
                            ->relationship('application', 'id')
                            ->disabled()
                            ->required(),

                        Forms\Components\Select::make('user_id')
                            ->label('Dilaporkan Oleh')
                            ->relationship('user', 'name')
                            ->disabled()
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Detail Application')
                    ->schema([
                        Forms\Components\Placeholder::make('application_details')
                            ->label('')
                            ->content(function ($record) {
                                if (!$record || !$record->application) {
                                    return 'Data application tidak ditemukan';
                                }

                                $app = $record->application;

                                $html = '<div class="space-y-2 text-sm">';

                                $html .= '<div class="font-semibold text-base mb-2">Informasi Application</div>';
                                $html .= '<div><strong>Status:</strong> <span class="px-2 py-1 rounded text-xs bg-gray-100">' . $app->status . '</span></div>';

                                if ($app->candidate && $app->candidate->user) {
                                    $html .= '<div><strong>Candidate:</strong> ' . $app->candidate->user->name . '</div>';
                                }

                                if ($app->jobPosting) {
                                    $html .= '<div><strong>Job:</strong> ' . ($app->jobPosting->title ?? '-') . '</div>';

                                    if ($app->jobPosting->company) {
                                        $html .= '<div><strong>Company:</strong> ' . $app->jobPosting->company->name . '</div>';
                                    }
                                }

                                $html .= '<div class="mt-4 font-semibold">Rating & Review</div>';

                                if ($app->rating_candidates) {
                                    $html .= '<div class="mt-2 p-3 bg-yellow-50 rounded">';
                                    $html .= '<div><strong>⭐ Rating dari Candidate:</strong> ' . $app->rating_candidates . '/5</div>';
                                    if ($app->review_candidate) {
                                        $html .= '<div class="mt-1"><strong>Review:</strong> ' . $app->review_candidate . '</div>';
                                    }
                                    $html .= '</div>';
                                }

                                if ($app->rating_company) {
                                    $html .= '<div class="mt-2 p-3 bg-blue-50 rounded">';
                                    $html .= '<div><strong>⭐ Rating dari Company:</strong> ' . $app->rating_company . '/5</div>';
                                    if ($app->review_company) {
                                        $html .= '<div class="mt-1"><strong>Review:</strong> ' . $app->review_company . '</div>';
                                    }
                                    $html .= '</div>';
                                }

                                if (!$app->rating_candidates && !$app->rating_company) {
                                    $html .= '<div class="text-gray-500 italic">Tidak ada rating atau review</div>';
                                }

                                $feedbackCount = $app->feedbackApplications()->count();
                                $html .= '<div class="mt-4 font-semibold">Feedback</div>';
                                if ($feedbackCount > 0) {
                                    $html .= '<div class="mt-2 p-3 bg-green-50 rounded">';
                                    $html .= '<div><strong>💬 Total Feedback:</strong> ' . $feedbackCount . ' feedback</div>';

                                    $feedbacks = $app->feedbackApplications()->with('feedback')->get();
                                    if ($feedbacks->isNotEmpty()) {
                                        $html .= '<ul class="mt-2 list-disc list-inside text-xs">';
                                        foreach ($feedbacks as $fb) {
                                            $html .= '<li>' . ($fb->feedback->name ?? 'Feedback') . ' (dari: ' . ($fb->given_by ?? '-') . ')</li>';
                                        }
                                        $html .= '</ul>';
                                    }
                                    $html .= '</div>';
                                } else {
                                    $html .= '<div class="text-gray-500 italic">Tidak ada feedback</div>';
                                }

                                $html .= '</div>';

                                return new \Illuminate\Support\HtmlString($html);
                            }),
                    ])
                    ->visible(fn($record) => $record && $record->application),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('reason')
                    ->label('Alasan Laporan')
                    ->limit(50)
                    ->searchable()
                    ->wrap()
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) > 50) {
                            return $state;
                        }
                        return null;
                    }),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'pending',
                        'heroicon-o-check-circle' => 'approved',
                        'heroicon-o-x-circle' => 'rejected',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Dilaporkan Oleh')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('application.candidate.user.name')
                    ->label('Candidate')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('application.jobPosting.company.name')
                    ->label('Company')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('application.rating_candidates')
                    ->label('Rating Candidate')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn($state) => $state ? $state . '/5' : '-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('application.rating_company')
                    ->label('Rating Company')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn($state) => $state ? $state . '/5' : '-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Laporan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Update')
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
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->multiple(),

                Tables\Filters\Filter::make('has_candidate_rating')
                    ->label('Ada Rating dari Candidate')
                    ->query(fn(Builder $query): Builder => $query->whereHas('application', function ($q) {
                        $q->whereNotNull('rating_candidates');
                    })),

                Tables\Filters\Filter::make('has_company_rating')
                    ->label('Ada Rating dari Company')
                    ->query(fn(Builder $query): Builder => $query->whereHas('application', function ($q) {
                        $q->whereNotNull('rating_company');
                    })),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Laporan')
                    ->modalDescription('Rating dan Review yang DILAPORKAN akan dihapus.')
                    ->modalSubmitActionLabel('Ya, Approve')
                    ->action(function (Reports $record) {
                        $record->update(['status' => 'approved']);

                        if ($record->application) {
                            $app = $record->application;
                            $reporter = $record->user;

                            if ($reporter->isUser() || $reporter->isCandidate()) {
                                // Candidate melaporkan → Hapus DARI company
                                $feedbackCount = $app->feedbackApplications()->where('given_by', 'company')->count();

                                $app->update([
                                    'rating_candidates' => null,
                                    'review_candidate' => null,
                                ]);

                                $app->feedbackApplications()->where('given_by', 'company')->delete();

                                $message = "Rating & review DARI COMPANY telah dihapus. {$feedbackCount} feedback dihapus.";
                            } elseif ($reporter->isCompany()) {
                                // Company melaporkan → Hapus DARI candidate
                                $feedbackCount = $app->feedbackApplications()->where('given_by', 'candidate')->count();

                                $app->update([
                                    'rating_company' => null,
                                    'review_company' => null,
                                ]);

                                $app->feedbackApplications()->where('given_by', 'candidate')->delete();

                                $message = "Rating & review DARI CANDIDATE telah dihapus. {$feedbackCount} feedback dihapus.";
                            } else {
                                $message = 'Laporan approved, namun role pelapor tidak dikenali.';
                            }
                        } else {
                            $message = 'Laporan approved, namun application tidak ditemukan.';
                        }

                        Notification::make()
                            ->success()
                            ->title('Laporan Approved')
                            ->body($message)
                            ->send();
                    })
                    ->visible(fn(Reports $record) => $record->status === 'pending'),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Laporan')
                    ->modalDescription('Rating dan Review akan tetap ditampilkan.')
                    ->modalSubmitActionLabel('Ya, Reject')
                    ->action(function (Reports $record) {
                        $record->update(['status' => 'rejected']);

                        Notification::make()
                            ->success()
                            ->title('Laporan Rejected')
                            ->body('Rating dan review tetap ditampilkan.')
                            ->send();
                    })
                    ->visible(fn(Reports $record) => $record->status === 'pending'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('bulk_approve')
                        ->label('Approve Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Approve Multiple Reports')
                        ->modalDescription('Rating dan review yang DILAPORKAN akan dihapus.')
                        ->action(function ($records) {
                            $count = 0;
                            $totalFeedback = 0;

                            foreach ($records as $record) {
                                $record->update(['status' => 'approved']);

                                if ($record->application) {
                                    $app = $record->application;
                                    $reporter = $record->user;

                                    if ($reporter->isUser() || $reporter->isCandidate()) {
                                        $totalFeedback += $app->feedbackApplications()->where('given_by', 'company')->count();

                                        $app->update([
                                            'rating_candidates' => null,
                                            'review_candidate' => null,
                                        ]);

                                        $app->feedbackApplications()->where('given_by', 'company')->delete();
                                        $count++;
                                    } elseif ($reporter->isCompany()) {
                                        $totalFeedback += $app->feedbackApplications()->where('given_by', 'candidate')->count();

                                        $app->update([
                                            'rating_company' => null,
                                            'review_company' => null,
                                        ]);

                                        $app->feedbackApplications()->where('given_by', 'candidate')->delete();
                                        $count++;
                                    }
                                }
                            }

                            Notification::make()
                                ->success()
                                ->title('Berhasil')
                                ->body("{$count} laporan approved. Total {$totalFeedback} feedback dihapus.")
                                ->send();
                        }),

                    Tables\Actions\BulkAction::make('bulk_reject')
                        ->label('Reject Terpilih')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['status' => 'rejected']);
                            }

                            Notification::make()
                                ->success()
                                ->title('Berhasil')
                                ->body('Semua laporan terpilih telah di-reject.')
                                ->send();
                        }),
                ]),
            ]);
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
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReports::route('/create'),
            'edit' => Pages\EditReports::route('/{record}/edit'),
            'view' => Pages\ViewReports::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['application.candidate.user', 'application.jobPosting.company', 'user']);
    }
}
