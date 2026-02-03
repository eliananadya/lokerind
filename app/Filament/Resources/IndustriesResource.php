<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IndustriesResource\Pages;
use App\Models\Industries;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IndustriesResource extends Resource
{
    protected static ?string $model = Industries::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Industri';

    protected static ?string $modelLabel = 'Industri';

    protected static ?string $pluralModelLabel = 'Industri';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?int $navigationSort = 2;

    // Form for Create and Edit
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Field for inputting the name of the industry
                Forms\Components\TextInput::make('name')
                    ->label('Industry Name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    // Table to display Industries data
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Display the name of the industry
                Tables\Columns\TextColumn::make('name')
                    ->label('Industry Name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                // Optionally, you can add filters here
            ])
            ->actions([
                // Action for editing the industry
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Bulk delete action
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // You can define relations here if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIndustries::route('/'),
            'create' => Pages\CreateIndustries::route('/create'),
            'edit' => Pages\EditIndustries::route('/{record}/edit'),
        ];
    }
}
