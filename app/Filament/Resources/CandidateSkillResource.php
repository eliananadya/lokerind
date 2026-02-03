<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CandidateSkillResource\Pages;
use App\Filament\Resources\CandidateSkillResource\RelationManagers;
use App\Models\CandidatesSkills;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CandidateSkillResource extends Resource
{
    protected static ?string $model = CandidatesSkills::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationLabel = 'Keahlian Kandidat';

    protected static ?string $modelLabel = 'Keahlian Kandidat';

    protected static ?string $pluralModelLabel = 'Keahlian Kandidat';

    protected static ?string $navigationGroup = 'Relasi & Detail';

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
            'index' => Pages\ListCandidateSkills::route('/'),
            'create' => Pages\CreateCandidateSkill::route('/create'),
            'edit' => Pages\EditCandidateSkill::route('/{record}/edit'),
        ];
    }
}
