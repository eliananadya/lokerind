<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CitiesResource\Pages;
use App\Models\Cities;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CitiesResource extends Resource
{
    protected static ?string $model = Cities::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationLabel = 'Kota';

    protected static ?string $modelLabel = 'Kota';

    protected static ?string $pluralModelLabel = 'Kota';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?int $navigationSort = 1;

    // Form for Create and Edit
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Field for inputting city name
                Forms\Components\TextInput::make('name')
                    ->label('City Name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    // Table to display Cities data
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Display city name
                Tables\Columns\TextColumn::make('name')
                    ->label('City Name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                // Optionally, you can add filters here
            ])
            ->actions([
                // Action for editing the city
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
            // Optionally, you can define relations here if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCities::route('/create'),
            'edit' => Pages\EditCities::route('/{record}/edit'),
        ];
    }
}
