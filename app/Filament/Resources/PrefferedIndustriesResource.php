<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrefferedIndustriesResource\Pages;
use App\Filament\Resources\PrefferedIndustriesResource\RelationManagers;
use App\Models\PrefferedIndustries;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PrefferedIndustriesResource extends Resource
{
    protected static ?string $model = PrefferedIndustries::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationLabel = 'Preferensi Industri';

    protected static ?string $modelLabel = 'Preferensi Industri';

    protected static ?string $pluralModelLabel = 'Preferensi Industri';

    protected static ?string $navigationGroup = 'Preferensi Kandidat';

    protected static ?int $navigationSort = 2;

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
            'index' => Pages\ListPrefferedIndustries::route('/'),
            'create' => Pages\CreatePrefferedIndustries::route('/create'),
            'edit' => Pages\EditPrefferedIndustries::route('/{record}/edit'),
        ];
    }
}
