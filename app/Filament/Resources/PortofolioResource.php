<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PortofolioResource\Pages;
use App\Models\Portofolios;
use App\Models\Candidates;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup;

class PortofolioResource extends Resource
{
    protected static ?string $model = Portofolios::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Portofolio';

    protected static ?string $modelLabel = 'Portofolio';

    protected static ?string $pluralModelLabel = 'Portofolio';

    protected static ?string $navigationGroup = 'Relasi & Detail';

    protected static ?int $navigationSort = 5;

    // Form for creating or editing portfolios
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('file')
                    ->label('Portfolio File')
                    ->image() // Ensure it is an image file
                    ->required()
                    ->disk('public')
                    ->directory('portofolios')
                    ->maxSize(10240), // Max file size in KB
                Forms\Components\TextInput::make('caption')
                    ->label('Caption')
                    ->required(),
                Forms\Components\Select::make('candidates_id')
                    ->label('Candidate')
                    ->options(Candidates::all()->pluck('name', 'id')) // Dynamically load candidate options
                    ->searchable()
                    ->required(),
            ]);
    }

    // Table for listing portfolios
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('caption')
                    ->label('Caption')
                    ->searchable(),
                ImageColumn::make('file')
                    ->label('Portfolio Image')
                    ->url(fn($record) => asset('storage/portofolios/' . $record->file)), // Correct URL generation
                TextColumn::make('candidate.name')
                    ->label('Candidate')
                    ->sortable(),
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
            'index' => Pages\ListPortofolios::route('/'),
            'create' => Pages\CreatePortofolio::route('/create'),
            'edit' => Pages\EditPortofolio::route('/{record}/edit'),
        ];
    }
}
