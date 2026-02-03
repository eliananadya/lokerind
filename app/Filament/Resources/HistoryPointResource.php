<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HistoryPointResource\Pages;
use App\Filament\Resources\HistoryPointResource\RelationManagers;
use App\Models\HistoryPoint;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HistoryPointResource extends Resource
{
    protected static ?string $model = HistoryPoint::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Riwayat Poin';

    protected static ?string $modelLabel = 'Riwayat Poin';

    protected static ?string $pluralModelLabel = 'Riwayat Poin';

    protected static ?string $navigationGroup = 'Lain-lain';

    protected static ?int $navigationSort = 3;

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
            'index' => Pages\ListHistoryPoints::route('/'),
            'create' => Pages\CreateHistoryPoint::route('/create'),
            'edit' => Pages\EditHistoryPoint::route('/{record}/edit'),
        ];
    }
}
