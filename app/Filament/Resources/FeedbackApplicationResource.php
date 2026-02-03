<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedbackApplicationResource\Pages;
use App\Filament\Resources\FeedbackApplicationResource\RelationManagers;
use App\Models\FeedbackApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeedbackApplicationResource extends Resource
{
    protected static ?string $model = FeedbackApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationLabel = 'Umpan Balik Lamaran';

    protected static ?string $modelLabel = 'Umpan Balik Lamaran';

    protected static ?string $pluralModelLabel = 'Umpan Balik Lamaran';

    protected static ?string $navigationGroup = 'Feedback & Laporan';

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
            'index' => Pages\ListFeedbackApplications::route('/'),
            'create' => Pages\CreateFeedbackApplication::route('/create'),
            'edit' => Pages\EditFeedbackApplication::route('/{record}/edit'),
        ];
    }
}
