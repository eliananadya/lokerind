<?php

namespace App\Filament\Resources;

use App\Models\Skills;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\SkillsResource\Pages;

class SkillsResource extends Resource
{
    protected static ?string $model = Skills::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Keahlian';

    protected static ?string $modelLabel = 'Keahlian';

    protected static ?string $pluralModelLabel = 'Keahlian';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Field for Skill Name
                Forms\Components\TextInput::make('name')
                    ->label('Skill Name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Display skill name in the table
                Tables\Columns\TextColumn::make('name')
                    ->label('Skill Name')
                    ->searchable()  // Allow searching by skill name
                    ->sortable(),   // Allow sorting by skill name
            ])
            ->filters([
                // Optionally, you can add filters here
            ])
            ->actions([
                // Action for editing the skill
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Bulk action to delete multiple skills
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Optionally, you can add relations here if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSkills::route('/'),
            'create' => Pages\CreateSkills::route('/create'),
            'edit' => Pages\EditSkills::route('/{record}/edit'),
        ];
    }
}
