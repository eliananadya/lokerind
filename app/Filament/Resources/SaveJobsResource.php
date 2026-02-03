<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaveJobsResource\Pages;
use App\Filament\Resources\SaveJobsResource\RelationManagers;
use App\Models\SaveJobs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SaveJobsResource extends Resource
{
    protected static ?string $model = SaveJobs::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';

    protected static ?string $navigationLabel = 'Lowongan Tersimpan';

    protected static ?string $modelLabel = 'Lowongan Tersimpan';

    protected static ?string $pluralModelLabel = 'Lowongan Tersimpan';

    protected static ?string $navigationGroup = 'Manajemen Lowongan';

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
            'index' => Pages\ListSaveJobs::route('/'),
            'create' => Pages\CreateSaveJobs::route('/create'),
            'edit' => Pages\EditSaveJobs::route('/{record}/edit'),
        ];
    }
}
