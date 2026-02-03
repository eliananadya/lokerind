<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrefferedCityResource\Pages;
use App\Filament\Resources\PrefferedCityResource\RelationManagers;
use App\Models\PrefferedCities;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PrefferedCityResource extends Resource
{
    protected static ?string $model = PrefferedCities::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'Preferensi Kota';

    protected static ?string $modelLabel = 'Preferensi Kota';

    protected static ?string $pluralModelLabel = 'Preferensi Kota';

    protected static ?string $navigationGroup = 'Preferensi Kandidat';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPrefferedCities::route('/'),
            'create' => Pages\CreatePrefferedCity::route('/create'),
            'edit' => Pages\EditPrefferedCity::route('/{record}/edit'),
        ];
    }
}
