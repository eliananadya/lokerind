<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CandidateResource\Pages;
use App\Models\Candidates;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn; // Corrected namespace for TextColumn
use Illuminate\Database\Eloquent\Builder;

class CandidateResource extends Resource
{
    protected static ?string $model = Candidates::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Kandidat';

    protected static ?string $modelLabel = 'Kandidat';

    protected static ?string $pluralModelLabel = 'Kandidat';

    protected static ?string $navigationGroup = 'Manajemen Pengguna';

    protected static ?int $navigationSort = 2;

    // Form for creating or editing candidates
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required(),
                Forms\Components\Select::make('gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                    ])
                    ->label('Gender')
                    ->required(),
                Forms\Components\TextArea::make('description')
                    ->label('Description')
                    ->required(),
                Forms\Components\DatePicker::make('birth_date')
                    ->label('Birth Date')
                    ->required(),
                Forms\Components\TextInput::make('level_mandarin')
                    ->label('Mandarin Level')
                    ->required(),
                Forms\Components\TextInput::make('level_english')
                    ->label('English Level')
                    ->required(),
                Forms\Components\TextInput::make('point')
                    ->label('Point')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('avg_rating')
                    ->label('Average Rating')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('min_height')
                    ->label('Min Height (cm)')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('min_weight')
                    ->label('Min Weight (kg)')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('min_salary')
                    ->label('Min Salary')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('percentase_acc')
                    ->label('Percentage Acceptance')
                    ->numeric()
                    ->required(),
                Forms\Components\Hidden::make('user_id')
                    ->default(fn() => auth()->id()) // Automatically set user_id to the logged-in user
                    ->required(),
            ]);
    }

    // Table for listing candidates
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('gender')
                    ->label('Gender'),
                TextColumn::make('birth_date')
                    ->label('Birth Date')
                    ->date(),
                TextColumn::make('level_mandarin')
                    ->label('Mandarin Level'),
                TextColumn::make('level_english')
                    ->label('English Level'),
                TextColumn::make('avg_rating')
                    ->label('Average Rating'),
                TextColumn::make('point')
                    ->label('Point'),
            ])
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    // Relation Managers for handling related models (if any)
    public static function getRelations(): array
    {
        return [
            // Define related models if needed
        ];
    }

    // Define pages (List, Create, Edit)
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCandidates::route('/'),
            'create' => Pages\CreateCandidate::route('/create'),
            'edit' => Pages\EditCandidate::route('/{record}/edit'),
        ];
    }
}
