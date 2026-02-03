<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrefferedDaysResource\Pages;
use App\Filament\Resources\PrefferedDaysResource\RelationManagers;
use App\Models\PrefferedDays;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PrefferedDaysResource extends Resource
{
    protected static ?string $model = PrefferedDays::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'Preferensi Hari';

    protected static ?string $modelLabel = 'Preferensi Hari';

    protected static ?string $pluralModelLabel = 'Preferensi Hari';

    protected static ?string $navigationGroup = 'Preferensi Kandidat';

    protected static ?int $navigationSort = 4;

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
            'index' => Pages\ListPrefferedDays::route('/'),
            'create' => Pages\CreatePrefferedDays::route('/create'),
            'edit' => Pages\EditPrefferedDays::route('/{record}/edit'),
        ];
    }
}
