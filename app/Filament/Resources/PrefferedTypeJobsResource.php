<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrefferedTypeJobsResource\Pages;
use App\Filament\Resources\PrefferedTypeJobsResource\RelationManagers;
use App\Models\PrefferedTypeJobs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PrefferedTypeJobsResource extends Resource
{
    protected static ?string $model = PrefferedTypeJobs::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Preferensi Tipe Kerja';

    protected static ?string $modelLabel = 'Preferensi Tipe Kerja';

    protected static ?string $pluralModelLabel = 'Preferensi Tipe Kerja';

    protected static ?string $navigationGroup = 'Preferensi Kandidat';

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
            'index' => Pages\ListPrefferedTypeJobs::route('/'),
            'create' => Pages\CreatePrefferedTypeJobs::route('/create'),
            'edit' => Pages\EditPrefferedTypeJobs::route('/{record}/edit'),
        ];
    }
}
