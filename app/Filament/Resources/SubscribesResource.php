<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscribesResource\Pages;
use App\Filament\Resources\SubscribesResource\RelationManagers;
use App\Models\Subscribes;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubscribesResource extends Resource
{
    protected static ?string $model = Subscribes::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Langganan';

    protected static ?string $modelLabel = 'Langganan';

    protected static ?string $pluralModelLabel = 'Langganan';

    protected static ?string $navigationGroup = 'Lain-lain';

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
            'index' => Pages\ListSubscribes::route('/'),
            'create' => Pages\CreateSubscribes::route('/create'),
            'edit' => Pages\EditSubscribes::route('/{record}/edit'),
        ];
    }
}
